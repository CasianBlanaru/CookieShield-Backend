<?php

namespace Tests\Unit;

use App\Models\Config;
use App\Services\ConfigService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan; // For migrations if needed without RefreshDatabase for every test
use Carbon\Carbon;

class ConfigServiceTest extends TestCase
{
    use RefreshDatabase; // Using RefreshDatabase for simplicity

    private ConfigService $configService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configService = new ConfigService();
    }

    public function test_get_default_config_data_returns_correct_structure()
    {
        $apiKey = 'test-api-key';
        $version = '1.0.0';
        $data = $this->configService->getDefaultConfigData($apiKey, $version);

        $this->assertArrayHasKey('api_key', $data);
        $this->assertEquals($apiKey, $data['api_key']);
        $this->assertArrayHasKey('version', $data);
        $this->assertEquals($version, $data['version']);
        $this->assertArrayHasKey('categories', $data);
        $this->assertArrayHasKey('translations', $data);

        // Assert default values for categories
        $this->assertEquals(['necessary', 'performance', 'functional', 'advertising'], $data['categories']);

        // Assert structure of translations (at least 'en' and 'de' keys)
        $this->assertArrayHasKey('en', $data['translations']);
        $this->assertArrayHasKey('de', $data['translations']);
        $this->assertArrayHasKey('banner_message', $data['translations']['en']);
    }

    public function test_find_or_create_config_creates_new_if_not_exists()
    {
        $apiKey = 'new-api-key';
        $version = '1.0.1';

        $config = $this->configService->findOrCreateConfig($apiKey, $version);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertTrue($config->wasRecentlyCreated); // Check if it was just created
        $this->assertDatabaseHas('configs', [
            'api_key' => $apiKey,
            'version' => $version,
        ]);

        // Check if default categories and translations were applied
        $defaultData = $this->configService->getDefaultConfigData($apiKey, $version);
        $this->assertEquals($defaultData['categories'], $config->categories);
        $this->assertEquals($defaultData['translations'], $config->translations);
    }

    public function test_find_or_create_config_returns_existing_without_save_if_version_matches()
    {
        $apiKey = 'existing-key';
        $version = '1.0.0';
        $defaultData = $this->configService->getDefaultConfigData($apiKey, $version);

        // Manually create a config ensuring all fields are present
        $existingConfig = Config::create([
            'api_key' => $apiKey,
            'version' => $version,
            'consent_type' => 'opt-in',
            'consent_lifetime' => 180,
            'is_granular_policy' => true,
            'google_consent_mode_enabled' => false,
            'microsoft_consent_mode_enabled' => false,
            'bulk_consent' => $defaultData['bulk_consent'] ?? [],
            'categories' => $defaultData['categories'],
            'translations' => $defaultData['translations'],
        ]);

        // Allow a small window for timestamp comparison if needed, though direct check is better
        $originalUpdatedAt = $existingConfig->updated_at;
        Carbon::setTestNow(Carbon::now()->addSeconds(5)); // Advance time slightly to ensure updated_at would change if saved

        $config = $this->configService->findOrCreateConfig($apiKey, $version);

        Carbon::setTestNow(); // Reset Carbon's test time

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals($apiKey, $config->api_key);
        $this->assertEquals($version, $config->version);
        $this->assertFalse($config->wasRecentlyCreated);

        // Fetch fresh instance to compare updated_at
        $freshConfig = Config::find($existingConfig->id);
        $this->assertEquals($originalUpdatedAt->timestamp, $freshConfig->updated_at->timestamp, "Config should not have been saved again.");
    }

    public function test_find_or_create_config_updates_version_and_saves_if_version_differs()
    {
        $apiKey = 'update-key';
        $initialVersion = '1.0.0';
        $newVersion = '1.1.0';
        $defaultData = $this->configService->getDefaultConfigData($apiKey, $initialVersion);

        $existingConfig = Config::create([
            'api_key' => $apiKey,
            'version' => $initialVersion,
            'consent_type' => 'opt-in',
            'consent_lifetime' => 180,
            'is_granular_policy' => true,
            'google_consent_mode_enabled' => false,
            'microsoft_consent_mode_enabled' => false,
            'bulk_consent' => $defaultData['bulk_consent'] ?? [],
            'categories' => $defaultData['categories'],
            'translations' => $defaultData['translations'],
        ]);

        $originalUpdatedAt = $existingConfig->updated_at;

        // Ensure some time passes so updated_at would definitely change if saved
        // This might not be strictly necessary if the version change itself is enough to trigger save
        // but good for robustness of the test for updated_at check.
        sleep(1); // Pause for 1 second to ensure timestamp difference

        $config = $this->configService->findOrCreateConfig($apiKey, $newVersion);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertEquals($newVersion, $config->version);
        $this->assertFalse($config->wasRecentlyCreated); // It existed, was just updated

        $this->assertDatabaseHas('configs', [
            'api_key' => $apiKey,
            'version' => $newVersion,
        ]);

        // Check that updated_at has changed
        $this->assertNotEquals($originalUpdatedAt->timestamp, $config->updated_at->timestamp);
    }
}
