<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Bundle\FHIRBundle;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Configuration;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\FHIRExtension;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Property-based test for FHIR Bundle configuration schema validation.
 *
 * **Feature: repository-reorganization, Property 7: Configuration schema validation**
 *
 * Tests that the FHIR bundle configuration schema properly validates
 * configuration values and rejects invalid configurations while accepting
 * valid ones.
 */
class FHIRBundleConfigurationValidationTest extends TestCase
{
    use TestTrait;

    /**
     * Test that valid FHIR bundle configurations are accepted.
     *
     * **Feature: repository-reorganization, Property 7: Configuration schema validation**
     * **Validates: Requirements 2.5**
     *
     * Property: For any valid FHIR configuration values, the configuration
     * schema should accept them and process them correctly.
     */
    public function testValidConfigurationsAreAccepted(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']), // Valid FHIR versions
            Generator\bool(), // Validation enabled
            Generator\bool(), // Strict mode
            Generator\string(), // Output directory
            Generator\string(),  // Cache directory
        )->then(function(string $fhirVersion, bool $validationEnabled, bool $strictMode, string $outputDir, string $cacheDir): void {
            $configuration = new Configuration();
            $processor     = new Processor();

            $config = [
                'output_directory' => '/tmp/' . $outputDir,
                'cache_directory'  => '/tmp/' . $cacheDir,
                'default_version'  => $fhirVersion,
                'validation'       => [
                    'enabled'     => $validationEnabled,
                    'strict_mode' => $strictMode,
                ],
                'packages' => [
                    'hl7.fhir.r4b.core' => [
                        'version'     => '4.3.0',
                        'auto_update' => false,
                    ],
                ],
            ];

            // This should not throw an exception for valid configurations
            $processedConfig = $processor->processConfiguration($configuration, [$config]);

            // Verify the processed configuration contains expected values
            self::assertEquals($fhirVersion, $processedConfig['default_version']);
            self::assertEquals($validationEnabled, $processedConfig['validation']['enabled']);
            self::assertEquals($strictMode, $processedConfig['validation']['strict_mode']);
            self::assertStringContainsString($outputDir, $processedConfig['output_directory']);
            self::assertStringContainsString($cacheDir, $processedConfig['cache_directory']);
        });
    }

    /**
     * Test that invalid FHIR versions are rejected.
     *
     * **Feature: repository-reorganization, Property 7: Configuration schema validation**
     * **Validates: Requirements 2.5**
     *
     * Property: For any invalid FHIR version, the configuration schema
     * should reject the configuration with an appropriate error.
     */
    public function testInvalidFHIRVersionsAreRejected(): void
    {
        $this->forAll(
            Generator\elements(['R3', 'R6', 'INVALID', 'r4', 'r4b', 'r5', '']), // Invalid FHIR versions
        )->then(function(string $invalidVersion): void {
            $configuration = new Configuration();
            $processor     = new Processor();

            $config = [
                'default_version' => $invalidVersion,
            ];

            // This should throw an InvalidConfigurationException
            $this->expectException(InvalidConfigurationException::class);
            $processor->processConfiguration($configuration, [$config]);
        });
    }

    /**
     * Test that the extension properly processes valid configurations.
     *
     * **Feature: repository-reorganization, Property 7: Configuration schema validation**
     * **Validates: Requirements 2.5**
     *
     * Property: For any valid configuration, the FHIR extension should
     * process it and set appropriate container parameters.
     */
    public function testExtensionProcessesValidConfigurations(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']), // Valid FHIR versions
            Generator\bool(), // Validation enabled
            Generator\bool(),  // Strict mode
        )->then(function(string $fhirVersion, bool $validationEnabled, bool $strictMode): void {
            $container = new ContainerBuilder();
            $extension = new FHIRExtension();

            // Set up basic parameters that the extension expects
            $container->setParameter('kernel.project_dir', '/tmp/test');
            $container->setParameter('kernel.cache_dir', '/tmp/test/cache');

            $config = [
                'output_directory' => '/tmp/test/output',
                'cache_directory'  => '/tmp/test/cache/fhir',
                'default_version'  => $fhirVersion,
                'validation'       => [
                    'enabled'     => $validationEnabled,
                    'strict_mode' => $strictMode,
                ],
            ];

            // This should not throw an exception
            $extension->load([$config], $container);

            // Verify parameters are set correctly
            self::assertEquals($fhirVersion, $container->getParameter('fhir.default_version'));
            self::assertEquals($validationEnabled, $container->getParameter('fhir.validation_enabled'));
            self::assertEquals($strictMode, $container->getParameter('fhir.validation_strict_mode'));
            self::assertEquals('/tmp/test/output', $container->getParameter('fhir.output_directory'));
            self::assertEquals('/tmp/test/cache/fhir', $container->getParameter('fhir.cache_directory'));
        });
    }

    /**
     * Test that default configuration values are properly applied.
     *
     * **Feature: repository-reorganization, Property 7: Configuration schema validation**
     * **Validates: Requirements 2.5**
     *
     * Property: When configuration values are not provided, the schema
     * should apply appropriate default values.
     */
    public function testDefaultConfigurationValuesAreApplied(): void
    {
        $configuration = new Configuration();
        $processor     = new Processor();

        // Empty configuration should use defaults
        $config = [];

        $processedConfig = $processor->processConfiguration($configuration, [$config]);

        // Verify default values are applied
        self::assertEquals('R4B', $processedConfig['default_version']);
        self::assertTrue($processedConfig['validation']['enabled']);
        self::assertFalse($processedConfig['validation']['strict_mode']);
        self::assertEquals('%kernel.project_dir%/output', $processedConfig['output_directory']);
        self::assertEquals('%kernel.cache_dir%/fhir', $processedConfig['cache_directory']);
    }
}
