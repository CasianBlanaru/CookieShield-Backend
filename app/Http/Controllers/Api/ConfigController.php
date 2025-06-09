<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Services\ConfigService;
use App\Http\Resources\ConfigResource; // Add this use statement
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; // JsonResponse might not be needed for all methods if returning resources

class ConfigController extends Controller
{
    protected ConfigService $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    /**
     * Get configuration.
     */
    public function getConfig(Request $request): ConfigResource // Update return type hint
    {
        $validated = $request->validate([
            'apiKey' => 'required|string',
            'v' => 'nullable|string',
        ]);

        $apiKey = $validated['apiKey'];
        $version = $validated['v'] ?? null;

        $config = $this->configService->findOrCreateConfig($apiKey, $version);
        return new ConfigResource($config); // Use ConfigResource
    }

    /**
     * Save configuration.
     */
    public function storeConfig(Request $request): ConfigResource // Update return type hint
    {
        $validatedData = $request->validate([
            'api_key' => 'required|string',
            'version' => 'nullable|string',
            // ... other validation rules ...
            'consent_type' => 'nullable|in:opt-in,opt-out',
            'consent_lifetime' => 'nullable|integer',
            'is_granular_policy' => 'nullable|boolean',
            'google_consent_mode_enabled' => 'nullable|boolean',
            'microsoft_consent_mode_enabled' => 'nullable|boolean',
            'bulk_consent' => 'nullable|array',
            'categories' => 'nullable|array',
            'scripts' => 'nullable|array',
            'translations' => 'nullable|array',
        ]);

        $apiKey = $validatedData['api_key'];
        $config = Config::firstOrNew(['api_key' => $apiKey]);

        $config->version = $validatedData['version'] ?? $config->version ?? '1.0.0';
        $config->consent_type = $validatedData['consent_type'] ?? $config->consent_type;
        $config->consent_lifetime = $validatedData['consent_lifetime'] ?? $config->consent_lifetime;
        $config->is_granular_policy = $validatedData['is_granular_policy'] ?? $config->is_granular_policy ?? false;
        $config->google_consent_mode_enabled = $validatedData['google_consent_mode_enabled'] ?? $config->google_consent_mode_enabled ?? false;
        $config->microsoft_consent_mode_enabled = $validatedData['microsoft_consent_mode_enabled'] ?? $config->microsoft_consent_mode_enabled ?? false;
        $config->bulk_consent = $validatedData['bulk_consent'] ?? $config->bulk_consent ?? [];
        $config->categories = $validatedData['categories'] ?? $config->categories ?? [];
        $config->scripts = $validatedData['scripts'] ?? $config->scripts ?? [];
        $config->translations = $validatedData['translations'] ?? $config->translations ?? [];

        $config->save();

        // Return the resource, Laravel will handle the 201 status code for new models if it's a POST request.
        // To be explicit for creation (POST):
        // return (new ConfigResource($config))->response()->setStatusCode(201);
        // For simplicity, let's assume Laravel's default handling is fine for now.
        // Let's try to be explicit with the status code for creation.
        if ($config->wasRecentlyCreated) {
            return (new ConfigResource($config))->response()->setStatusCode(201);
        }
        return new ConfigResource($config);
    }

    /**
     * List configurations (Admin function).
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection // Update return type hint
    {
        $configs = Config::all();
        return ConfigResource::collection($configs); // Use ConfigResource for collection
    }

    /**
     * Show a specific configuration (Admin function).
     */
    public function show(string $id): ConfigResource // Update return type hint
    {
        $config = Config::findOrFail($id);
        return new ConfigResource($config); // Use ConfigResource
    }

    /**
     * Update a configuration (Admin function).
     */
    public function update(Request $request, string $id): ConfigResource // Update return type hint
    {
        $config = Config::findOrFail($id);
        $validatedData = $request->validate([
            'version' => 'nullable|string',
            // ... other validation rules ...
            'consent_type' => 'nullable|in:opt-in,opt-out',
            'consent_lifetime' => 'nullable|integer',
            'is_granular_policy' => 'nullable|boolean',
            'google_consent_mode_enabled' => 'nullable|boolean',
            'microsoft_consent_mode_enabled' => 'nullable|boolean',
            'bulk_consent' => 'nullable|array',
            'categories' => 'nullable|array',
            'scripts' => 'nullable|array',
            'translations' => 'nullable|array',
        ]);

        $config->version = $validatedData['version'] ?? $config->version;
        $config->consent_type = $validatedData['consent_type'] ?? $config->consent_type;
        $config->consent_lifetime = $validatedData['consent_lifetime'] ?? $config->consent_lifetime;
        $config->is_granular_policy = $validatedData['is_granular_policy'] ?? $config->is_granular_policy;
        $config->google_consent_mode_enabled = $validatedData['google_consent_mode_enabled'] ?? $config->google_consent_mode_enabled;
        $config->microsoft_consent_mode_enabled = $validatedData['microsoft_consent_mode_enabled'] ?? $config->microsoft_consent_mode_enabled;
        $config->bulk_consent = $validatedData['bulk_consent'] ?? $config->bulk_consent;
        $config->categories = $validatedData['categories'] ?? $config->categories;
        $config->scripts = $validatedData['scripts'] ?? $config->scripts;
        $config->translations = $validatedData['translations'] ?? $config->translations;

        $config->save();
        return new ConfigResource($config); // Use ConfigResource
    }

    /**
     * Delete a configuration (Admin function).
     */
    public function destroy(string $id): JsonResponse // Stays as JsonResponse
    {
        $config = Config::findOrFail($id);
        $config->delete();
        return response()->json(['message' => 'Configuration deleted successfully']);
    }
}
