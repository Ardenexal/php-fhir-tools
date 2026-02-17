<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Test recipe installation simulation.
 *
 * This test simulates what Symfony Flex would do when installing
 * the FHIR bundle recipe.
 *
 * @author Ardenexal FHIRTools Team
 */
class RecipeInstallationTest extends TestCase
{
    private Filesystem $filesystem;

    private string $tempDir;

    private string $recipeDir;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->tempDir    = sys_get_temp_dir() . '/fhir_recipe_test_' . uniqid();
        $this->recipeDir  = __DIR__ . '/../../recipe/fhir-bundle/1.0';

        // Create temporary project directory
        $this->filesystem->mkdir($this->tempDir);
        $this->filesystem->mkdir($this->tempDir . '/config');
        $this->filesystem->mkdir($this->tempDir . '/config/packages');
    }

    protected function tearDown(): void
    {
        if ($this->filesystem->exists($this->tempDir)) {
            $this->filesystem->remove($this->tempDir);
        }
    }

    /**
     * Test simulated recipe installation process.
     */
    public function testRecipeInstallation(): void
    {
        // Simulate Flex copying files from recipe
        $this->simulateFlexInstallation();

        // Verify bundle registration
        $this->verifyBundleRegistration();

        // Verify configuration files
        $this->verifyConfigurationFiles();

        // Verify environment variables
        $this->verifyEnvironmentVariables();
    }

    /**
     * Simulate what Symfony Flex does during installation.
     */
    private function simulateFlexInstallation(): void
    {
        $manifest = json_decode(file_get_contents($this->recipeDir . '/manifest.json'), true);

        // Copy configuration files
        if (isset($manifest['copy-from-recipe'])) {
            foreach ($manifest['copy-from-recipe'] as $source => $target) {
                $sourcePath = $this->recipeDir . '/' . $source;
                $targetPath = $this->tempDir . '/' . str_replace('%CONFIG_DIR%', 'config', $target);

                if (is_dir($sourcePath)) {
                    $this->filesystem->mirror($sourcePath, $targetPath);
                }
            }
        }

        // Create bundles.php
        $bundlesContent = "<?php\n\nreturn [\n";
        if (isset($manifest['bundles'])) {
            foreach ($manifest['bundles'] as $bundle => $envs) {
                $bundlesContent .= "    {$bundle}::class => " . var_export($envs, true) . ",\n";
            }
        }
        $bundlesContent .= "];\n";

        file_put_contents($this->tempDir . '/config/bundles.php', $bundlesContent);

        // Create .env file with environment variables
        $envContent = '';
        if (isset($manifest['env'])) {
            foreach ($manifest['env'] as $key => $value) {
                $envContent .= "{$key}={$value}\n";
            }
        }

        file_put_contents($this->tempDir . '/.env', $envContent);
    }

    /**
     * Verify that bundle is properly registered.
     */
    private function verifyBundleRegistration(): void
    {
        $bundlesFile = $this->tempDir . '/config/bundles.php';
        self::assertFileExists($bundlesFile);

        $bundlesContent = file_get_contents($bundlesFile);
        self::assertNotFalse($bundlesContent, 'Should be able to read bundles.php');
        self::assertStringContainsString('Ardenexal\\FHIRTools\\Bundle\\FHIRBundle\\FHIRBundle', $bundlesContent);

        // Try to evaluate the PHP file safely
        $bundles = [];
        try {
            $bundles = include $bundlesFile;
        } catch (\Throwable $e) {
            // If include fails, just check the content
            self::assertStringContainsString("'all'", $bundlesContent);

            return;
        }

        if (is_array($bundles)) {
            $expectedBundle = 'Ardenexal\\FHIRTools\\Bundle\\FHIRBundle\\FHIRBundle';
            self::assertArrayHasKey($expectedBundle, $bundles);
            self::assertEquals(['all'], $bundles[$expectedBundle]);
        }
    }

    /**
     * Verify that configuration files are properly copied.
     */
    private function verifyConfigurationFiles(): void
    {
        $configFiles = [
            'config/packages/fhir.yaml',
            'config/packages/dev/fhir.yaml',
            'config/packages/prod/fhir.yaml',
            'config/packages/test/fhir.yaml',
        ];

        foreach ($configFiles as $configFile) {
            $fullPath = $this->tempDir . '/' . $configFile;
            self::assertFileExists($fullPath, "Configuration file {$configFile} should be copied");

            // Verify YAML is valid
            $config = Yaml::parseFile($fullPath);
            self::assertIsArray($config);
            self::assertArrayHasKey('fhir', $config);
        }
    }

    /**
     * Verify that environment variables are properly set.
     */
    private function verifyEnvironmentVariables(): void
    {
        $envFile = $this->tempDir . '/.env';
        self::assertFileExists($envFile);

        $envContent = file_get_contents($envFile);

        $requiredEnvVars = [
            'FHIR_OUTPUT_DIRECTORY',
            'FHIR_CACHE_DIRECTORY',
            'FHIR_DEFAULT_VERSION',
            'FHIR_VALIDATION_ENABLED',
            'FHIR_VALIDATION_STRICT_MODE',
        ];

        foreach ($requiredEnvVars as $envVar) {
            self::assertStringContainsString($envVar, $envContent, "Environment variable {$envVar} should be in .env file");
        }
    }

    /**
     * Test that configuration can be loaded and parsed correctly.
     */
    public function testConfigurationLoading(): void
    {
        $this->simulateFlexInstallation();

        $mainConfig = Yaml::parseFile($this->tempDir . '/config/packages/fhir.yaml');

        // Test that environment variable references are present
        self::assertStringContainsString('%env(FHIR_OUTPUT_DIRECTORY)%', $mainConfig['fhir']['output_directory']);
        self::assertStringContainsString('%env(FHIR_CACHE_DIRECTORY)%', $mainConfig['fhir']['cache_directory']);
        self::assertStringContainsString('%env(FHIR_DEFAULT_VERSION)%', $mainConfig['fhir']['default_version']);

        // Test validation configuration
        self::assertArrayHasKey('validation', $mainConfig['fhir']);
        self::assertArrayHasKey('enabled', $mainConfig['fhir']['validation']);
        self::assertArrayHasKey('strict_mode', $mainConfig['fhir']['validation']);

        // Test packages configuration
        self::assertArrayHasKey('packages', $mainConfig['fhir']);
        self::assertIsArray($mainConfig['fhir']['packages']);
        self::assertNotEmpty($mainConfig['fhir']['packages']);
    }

    /**
     * Test environment-specific configurations.
     */
    public function testEnvironmentSpecificConfigurations(): void
    {
        $this->simulateFlexInstallation();

        // Test dev configuration
        $devConfig = Yaml::parseFile($this->tempDir . '/config/packages/dev/fhir.yaml');
        self::assertFalse($devConfig['fhir']['validation']['strict_mode']);

        // Test prod configuration
        $prodConfig = Yaml::parseFile($this->tempDir . '/config/packages/prod/fhir.yaml');
        self::assertTrue($prodConfig['fhir']['validation']['enabled']);
        self::assertTrue($prodConfig['fhir']['validation']['strict_mode']);

        // Test test configuration
        $testConfig = Yaml::parseFile($this->tempDir . '/config/packages/test/fhir.yaml');
        self::assertStringContainsString('tests/output', $testConfig['fhir']['output_directory']);
        self::assertStringContainsString('test_fhir', $testConfig['fhir']['cache_directory']);
    }
}
