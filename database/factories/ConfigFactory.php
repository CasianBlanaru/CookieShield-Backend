<?php

namespace Database\Factories;

use App\Models\Config;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ConfigFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Config::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Default translations and categories similar to ConfigService
        $defaultTranslations = [
            'en' => [
                'banner_message' => 'We use cookies to enhance your experience. By continuing, you agree to our use of cookies.',
                'accept_all' => 'Accept All',
                'deny_all' => 'Deny All',
                'settings' => 'Cookie Settings',
                'settings_title' => 'Cookie Settings',
                'save' => 'Save Settings',
                'category_necessary' => 'Essential Cookies',
                'category_performance' => 'Performance Cookies',
                'category_functional' => 'Functional Cookies',
                'category_advertising' => 'Advertising Cookies'
            ],
            'de' => [
                'banner_message' => 'Wir verwenden Cookies, um Ihre Erfahrung zu verbessern. Durch die weitere Nutzung stimmen Sie der Verwendung von Cookies zu.',
                'accept_all' => 'Alle akzeptieren',
                'deny_all' => 'Alle ablehnen',
                'settings' => 'Cookie-Einstellungen',
                'settings_title' => 'Cookie-Einstellungen',
                'save' => 'Einstellungen speichern',
                'category_necessary' => 'Notwendige Cookies',
                'category_performance' => 'Performance-Cookies',
                'category_functional' => 'Funktionale Cookies',
                'category_advertising' => 'Werbe-Cookies'
            ]
        ];
        $defaultCategories = ['necessary', 'performance', 'functional', 'advertising'];

        return [
            'api_key' => $this->faker->unique()->apiKey, // Assumes faker has apiKey, otherwise Str::random(32)
            'version' => $this->faker->semver(true, true), // e.g., 1.2.3 or 1.0.0-beta
            'consent_type' => $this->faker->randomElement(['opt-in', 'opt-out']),
            'consent_lifetime' => $this->faker->numberBetween(30, 365),
            'is_granular_policy' => $this->faker->boolean,
            'google_consent_mode_enabled' => $this->faker->boolean,
            'microsoft_consent_mode_enabled' => $this->faker->boolean,
            'bulk_consent' => [], // Default to empty array or generate sample structure
            'categories' => $defaultCategories,
            'scripts' => [], // Default to empty array or generate sample structure
            'translations' => $defaultTranslations,
        ];
    }
}
