<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ConfigControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // Helper to get default data structure for assertions
    private function getDefaultConfigDataStructure()
    {
        return [
            'id',
            'api_key',
            'version',
            'consent_type',
            'consent_lifetime',
            'is_granular_policy',
            'google_consent_mode_enabled',
            'microsoft_consent_mode_enabled',
            'bulk_consent',
            'categories',
            'scripts',
            'translations',
            'created_at',
            'updated_at',
        ];
    }

    public function test_get_config_creates_new_for_new_api_key()
    {
        $apiKey = 'new-feature-key-' . $this->faker->uuid; // Ensure uniqueness
        $response = $this->getJson("/api/config?apiKey={$apiKey}&v=1.0.0");

        $response->assertStatus(200);
        $response->assertJsonPath('data.api_key', $apiKey);
        $response->assertJsonPath('data.version', '1.0.0');
        $response->assertJsonStructure(['data' => $this->getDefaultConfigDataStructure()]);
        $this->assertDatabaseHas('configs', ['api_key' => $apiKey, 'version' => '1.0.0']);
    }

    public function test_get_config_updates_version_for_existing_api_key()
    {
        $config = Config::factory()->create(['api_key' => 'feature-existing-key-' . $this->faker->uuid, 'version' => '1.0.0']);
        $response = $this->getJson("/api/config?apiKey={$config->api_key}&v=1.1.0");

        $response->assertStatus(200);
        $response->assertJsonPath('data.version', '1.1.0');
        $this->assertDatabaseHas('configs', ['api_key' => $config->api_key, 'version' => '1.1.0']);
    }

    public function test_store_config_creates_new_config()
    {
        $apiKey = 'store-new-key-' . $this->faker->uuid;
        $payload = [
            'api_key' => $apiKey,
            'version' => '1.0.0',
            'consent_type' => 'opt-in',
            'consent_lifetime' => 180,
            'is_granular_policy' => true,
            'google_consent_mode_enabled' => false,
            'microsoft_consent_mode_enabled' => true,
            'categories' => ['necessary', 'performance'],
            'translations' => ['en' => ['banner_message' => 'Test message']],
            'scripts' => [],
            'bulk_consent' => []
        ];

        $response = $this->postJson('/api/config', $payload);

        $response->assertStatus(201); // Assert 201 for creation
        $response->assertJsonPath('data.api_key', $apiKey);
        $response->assertJsonPath('data.consent_type', 'opt-in');
        $this->assertDatabaseHas('configs', ['api_key' => $apiKey, 'consent_type' => 'opt-in']);
    }

    public function test_store_config_updates_existing_config()
    {
        $apiKey = 'store-update-key-' . $this->faker->uuid;
        $config = Config::factory()->create(['api_key' => $apiKey, 'version' => '1.0.0', 'consent_type' => 'opt-in']);

        $payload = [
            'api_key' => $apiKey, // Important: Must match the existing config's api_key
            'version' => '1.0.1',
            'consent_type' => 'opt-out',
            // Include other fields as necessary to pass validation or update
            'consent_lifetime' => $config->consent_lifetime,
            'is_granular_policy' => $config->is_granular_policy,
            'google_consent_mode_enabled' => $config->google_consent_mode_enabled,
            'microsoft_consent_mode_enabled' => $config->microsoft_consent_mode_enabled,
            'categories' => $config->categories,
            'translations' => $config->translations,
            'scripts' => $config->scripts,
            'bulk_consent' => $config->bulk_consent,
        ];

        $response = $this->postJson('/api/config', $payload);

        $response->assertStatus(200); // Assert 200 for update
        $response->assertJsonPath('data.version', '1.0.1');
        $response->assertJsonPath('data.consent_type', 'opt-out');
        $this->assertDatabaseHas('configs', ['api_key' => $apiKey, 'version' => '1.0.1', 'consent_type' => 'opt-out']);
    }

    public function test_store_config_validation_failure()
    {
        $payload = ['version' => '1.0.0']; // Missing api_key
        $response = $this->postJson('/api/config', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['api_key']);
    }

    // Admin Endpoint Tests
    public function test_admin_can_list_configs()
    {
        $adminUser = User::factory()->create(['is_admin' => true]);
        Sanctum::actingAs($adminUser);
        Config::factory()->count(3)->create();

        // Assuming admin routes are prefixed with /api/admin/ (standard practice)
        // If not, this route needs adjustment.
        $response = $this->getJson('/api/admin/configs');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data'); // Assumes ConfigResource::collection returns data under 'data' key
        $response->assertJsonStructure(['data' => ['*' => $this->getDefaultConfigDataStructure()]]);
    }

    public function test_admin_can_show_config()
    {
        $adminUser = User::factory()->create(['is_admin' => true]);
        Sanctum::actingAs($adminUser);
        $config = Config::factory()->create();

        $response = $this->getJson("/api/admin/configs/{$config->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $config->id);
        $response->assertJsonStructure(['data' => $this->getDefaultConfigDataStructure()]);
    }


    public function test_admin_update_config_success()
    {
        $adminUser = User::factory()->create(['is_admin' => true]);
        Sanctum::actingAs($adminUser);
        $config = Config::factory()->create();

        $updatePayload = [
            'version' => '2.0.0',
            'consent_lifetime' => 30,
            // Include other fields from ConfigResource to ensure full update payload is valid
            'consent_type' => $config->consent_type,
            'is_granular_policy' => $config->is_granular_policy,
            'google_consent_mode_enabled' => $config->google_consent_mode_enabled,
            'microsoft_consent_mode_enabled' => $config->microsoft_consent_mode_enabled,
            'categories' => $config->categories,
            'translations' => $config->translations,
            'scripts' => $config->scripts,
            'bulk_consent' => $config->bulk_consent,
        ];

        // Assuming the route for admin update is /api/admin/configs/{id}
        $response = $this->putJson("/api/admin/configs/{$config->id}", $updatePayload);

        $response->assertStatus(200);
        $response->assertJsonPath('data.version', '2.0.0');
        $response->assertJsonPath('data.consent_lifetime', 30);
        $this->assertDatabaseHas('configs', ['id' => $config->id, 'version' => '2.0.0', 'consent_lifetime' => 30]);
    }

    public function test_admin_delete_config_success()
    {
        $adminUser = User::factory()->create(['is_admin' => true]);
        Sanctum::actingAs($adminUser);
        $config = Config::factory()->create();

        $response = $this->deleteJson("/api/admin/configs/{$config->id}");

        $response->assertStatus(200); // Or 204 No Content, depending on implementation
        $response->assertJson(['message' => 'Configuration deleted successfully']);
        $this->assertDatabaseMissing('configs', ['id' => $config->id]);
    }

    public function test_non_admin_cannot_access_admin_endpoints()
    {
        $user = User::factory()->create(['is_admin' => false]); // Non-admin user
        Sanctum::actingAs($user);
        $config = Config::factory()->create();

        $response = $this->getJson("/api/admin/configs");
        $response->assertStatus(403); // Forbidden

        $response = $this->putJson("/api/admin/configs/{$config->id}", ['version' => 'x']);
        $response->assertStatus(403);

        $response = $this->deleteJson("/api/admin/configs/{$config->id}");
        $response->assertStatus(403);
    }
}
