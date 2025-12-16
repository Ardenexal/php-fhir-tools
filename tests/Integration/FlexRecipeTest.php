<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Test Symfony Flex recipe structure and configuration.
 *
 * @author Ardenexal FHIRTools Team
 */
class FlexRecipeTest extends TestCase
{
    private string $recipeDir;

    protected function setUp(): void
    {
        $this->recipeDir = __DIR__ . '/../../config/recipes/fhir-bundle/1.0';
    }

    /**
     * Test that the recipe manifest exists and is valid JSON.
     */
    public function testManifestExists(): void
    {
        $manifestPath = $this->recipeDir . '/manifest.json';
        
        self::assertFileExists($manifestPath, 'Recipe manifest.json should exist');
        
        $content = file_get_contents($manifestPath);
        self::assertNotFalse($content, 'Should be able to read manifest.json');
        
        $manifest = json_decode($content, true);
        self::assertIsArray($manifest, 'Manifest should be valid JSON');
        self::assertArrayHasKey('bundles', $manifest, 'Manifest should have bundles key');
        self::assertArrayHasKey('copy-from-recipe', $manifest, 'Manifest should have copy-from-recipe key');
        self::assertArrayHasKey('env', $manifest, 'Manifest should have env key');
    }

    /**
     * Test that bundle registration is correct.
     */
    public function testBundleRegistration(): void
    {
        $manifestPath = $this->recipeDir . '/manifest.json';
        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        $expectedBundle = 'Ardenexal\\FHIRTools\\Bundle\\FHIRBundle\\FHIRBundle';
        self::assertArrayHasKey($expectedBundle, $manifest['bundles']);
        self::assertEquals(['all'], $manifest['bundles'][$expectedBundle]);
    }

    /**
     * Test that required environment variables are defined.
     */
    public function testEnvironmentVariables(): void
    {
        $manifestPath = $this->recipeDir . '/manifest.json';
        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        $requiredEnvVars = [
            'FHIR_OUTPUT_DIRECTORY',
            'FHIR_CACHE_DIRECTORY',
            'FHIR_DEFAULT_VERSION',
            'FHIR_VALIDATION_ENABLED',
            'FHIR_VALIDATION_STRICT_MODE'
        ];
        
        foreach ($requiredEnvVars as $envVar) {
            self::assertArrayHasKey($envVar, $manifest['env'], "Environment variable {$envVar} should be defined");
        }
    }

    /**
     * Test that configuration files exist.
     */
    public function testConfigurationFiles(): void
    {
        $configFiles = [
            'config/packages/fhir.yaml',
            'config/packages/dev/fhir.yaml',
            'config/packages/prod/fhir.yaml',
            'config/packages/test/fhir.yaml'
        ];
        
        foreach ($configFiles as $configFile) {
            $fullPath = $this->recipeDir . '/' . $configFile;
            self::assertFileExists($fullPath, "Configuration file {$configFile} should exist");
            
            // Validate YAML syntax
            $content = file_get_contents($fullPath);
            self::assertNotFalse($content, "Should be able to read {$configFile}");
            
            $yaml = Yaml::parse($content);
            self::assertIsArray($yaml, "Configuration file {$configFile} should be valid YAML");
            self::assertArrayHasKey('fhir', $yaml, "Configuration file {$configFile} should have fhir key");
        }
    }

    /**
     * Test that main configuration has required structure.
     */
    public function testMainConfigurationStructure(): void
    {
        $configPath = $this->recipeDir . '/config/packages/fhir.yaml';
        $config = Yaml::parseFile($configPath);
        
        $fhirConfig = $config['fhir'];
        
        // Test required top-level keys
        $requiredKeys = ['output_directory', 'cache_directory', 'default_version', 'validation', 'packages'];
        foreach ($requiredKeys as $key) {
            self::assertArrayHasKey($key, $fhirConfig, "Main config should have {$key} key");
        }
        
        // Test validation structure
        self::assertArrayHasKey('enabled', $fhirConfig['validation']);
        self::assertArrayHasKey('strict_mode', $fhirConfig['validation']);
        
        // Test packages structure
        self::assertIsArray($fhirConfig['packages']);
        self::assertNotEmpty($fhirConfig['packages'], 'Should have at least one package configured');
    }

    /**
     * Test that documentation files exist.
     */
    public function testDocumentationFiles(): void
    {
        $docFiles = [
            'README.md',
            'post-install.txt'
        ];
        
        foreach ($docFiles as $docFile) {
            $fullPath = $this->recipeDir . '/' . $docFile;
            self::assertFileExists($fullPath, "Documentation file {$docFile} should exist");
            
            $content = file_get_contents($fullPath);
            self::assertNotEmpty($content, "Documentation file {$docFile} should not be empty");
        }
    }

    /**
     * Test that gitignore entries are appropriate.
     */
    public function testGitignoreEntries(): void
    {
        $manifestPath = $this->recipeDir . '/manifest.json';
        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        self::assertArrayHasKey('gitignore', $manifest);
        self::assertContains('/output/', $manifest['gitignore']);
        self::assertContains('/var/cache/fhir/', $manifest['gitignore']);
    }

    /**
     * Test that aliases are defined for easier installation.
     */
    public function testAliases(): void
    {
        $manifestPath = $this->recipeDir . '/manifest.json';
        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        self::assertArrayHasKey('aliases', $manifest);
        self::assertContains('fhir', $manifest['aliases']);
        self::assertContains('fhir-tools', $manifest['aliases']);
        self::assertContains('fhir-bundle', $manifest['aliases']);
    }
}