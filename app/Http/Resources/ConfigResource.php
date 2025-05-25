<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'api_key' => $this->api_key,
            'version' => $this->version,
            'consent_type' => $this->consent_type,
            'consent_lifetime' => $this->consent_lifetime,
            'is_granular_policy' => (bool) $this->is_granular_policy, // Cast boolean
            'google_consent_mode_enabled' => (bool) $this->google_consent_mode_enabled, // Cast boolean
            'microsoft_consent_mode_enabled' => (bool) $this->microsoft_consent_mode_enabled, // Cast boolean
            'bulk_consent' => $this->bulk_consent,
            'categories' => $this->categories,
            'scripts' => $this->scripts,
            'translations' => $this->translations,
            'created_at' => $this->created_at->toIso8601String(), // Standardize date format
            'updated_at' => $this->updated_at->toIso8601String(), // Standardize date format
        ];
    }
}
