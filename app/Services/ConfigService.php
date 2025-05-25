<?php

namespace App\Services;

use App\Models\Config;
use Illuminate\Support\Arr; // Or use Illuminate\Support\Facades\Arr; if preferred

class ConfigService
{
    public function getDefaultConfigData(string $apiKey, ?string $version = '1.0.0'): array
    {
        // Logic to be ported from ConfigController@getConfig for default translations and categories
        // See details below.
        return [
            'api_key' => $apiKey, // Ensure apiKey is part of the default data
            'version' => $version ?? '1.0.0',
            'categories' => ['necessary', 'performance', 'functional', 'advertising'],
            'translations' => [
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
            ]
        ];
    }

    public function findOrCreateConfig(string $apiKey, ?string $version): Config
    {
        $config = Config::firstOrNew(['api_key' => $apiKey]);
        $needsSave = false;

        if (!$config->exists) {
            $defaultData = $this->getDefaultConfigData($apiKey, $version);
            // Use Arr::except to avoid mass assignment issues if $fillable is not perfectly set up,
            // though for default data it's generally safer.
            $config->fill(Arr::except($defaultData, ['api_key']));
            // Ensure version is set, defaulting if $version is null
            $config->version = $defaultData['version']; 
            $needsSave = true;
        } elseif ($version && $config->version !== $version) {
            $config->version = $version;
            $needsSave = true;
        }

        if ($needsSave) {
            $config->save();
        }

        return $config;
    }
}
