<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Bundle\FHIRBundle;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Configuration;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\FHIRExtension;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Property-based test for configuration compatibility across Symfony versions.
 *
 * **Feature: repository-reorganization, Property 11: Configuration compatibility**
 *
 * Tests that the FHIR bundle configuration works consistently across
 * different Symfony versions without breaking changes.
 */
class ConfigurationCompatibilityTest extends TestCase
{
    use TestTrait;

    /**
     * Test that configuration syntax is compatible across Symfony versions.
     *
     * **Feature: repository-reorganization, Property 11: Configuration compatibility**
     * **Validates: Requirements 3.4**
     *
     * Property: For any valid configuration, the bundle should process it
     * consistently across different Symfony versions.
     */
    public function testConfigurationSyntaxCompatibility(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']), // FHIR versions
            Generator\bool(), // Validation enabled
            Generator\bool(), // Strict mode
            Generator\elements(['/tmp/output', '/var/output', './output']), // Output directories
            Generator\elements(['/tmp/cache', '/var/cache', './cache']), // Cache directories
        )->then(function(string $fhirVersion, bool $validationEnabled, bool $strictMode, string $outputDir, string $cacheDir): void {
            $configuration = new Configuration();
            $processor     = new Processor();

            // Test various configuration formats that should work across versions
            $configs = [
                // Minimal configuration
                [],

                // Basic configuration
                [
                    'default_version' => $fhirVersion,
                    'validation'      => [
                        'enabled'     => $validationEnabled,
                        'strict_mode' => $strictMode,
                    ],
                ],

                // Full configuration
                [
                    'output_directory' => $outputDir,
                    'cache_directory'  => $cacheDir,
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
                ],
            ];

            foreach ($configs as $config) {
                // This should work consistently across Symfony versions
                $processedConfig = $processor->processConfiguration($configuration, [$config]);

                // Verify that essential configuration is always present
                self::assertArrayHasKey('default_version', $processedConfig);
                self::assertArrayHasKey('validation', $processedConfig);
                self::assertArrayHasKey('enabled', $processedConfig['validation']);
                self::assertArrayHasKey('strict_mode', $processedConfig['validation']);

                // Verify that defaults are applied when not specified
                if (empty($config)) {
                    self::assertEquals('R4B', $processedConfig['default_version']);
                    self::assertTrue($processedConfig['validation']['enabled']);
                    self::assertFalse($processedConfig['validation']['strict_mode']);
                }
            }
        });
    }

    /**
     * Test that service container configuration is compatible across versions.
     *
     * **Feature: repository-reorganization, Property 11: Configuration compatibility**
     * **Validates: Requirements 3.4**
     *
     * Property: Service container configuration should work consistently
     * across different Symfony versions without breaking changes.
     */
    public function testServiceContainerCompatibility(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']), // FHIR versions
            Generator\bool(), // Validation enabled
            Generator\bool(),  // Strict mode
        )->then(function(string $fhirVersion, bool $validationEnabled, bool $strictMode): void {
            // Create container and bundle
            $container = new ContainerBuilder();
            $bundle    = new FHIRBundle();
            $extension = new FHIRExtension();

            // Set up basic parameters that work across Symfony versions
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

            // This should work across different Symfony versions
            $bundle->build($container);
            $extension->load([$config], $container);

            // Verify that parameters are set consistently
            self::assertEquals($fhirVersion, $container->getParameter('fhir.default_version'));
            self::assertEquals($validationEnabled, $container->getParameter('fhir.validation_enabled'));
            self::assertEquals($strictMode, $container->getParameter('fhir.validation_strict_mode'));

            // Verify that version-specific parameters are handled gracefully
            self::assertTrue($container->hasParameter('fhir.symfony_version'));
            self::assertTrue($container->hasParameter('fhir.is_symfony_64_or_higher'));
            self::assertTrue($container->hasParameter('fhir.is_symfony_70_or_higher'));
            self::assertTrue($container->hasParameter('fhir.is_symfony_74_or_higher'));

            // Verify that services are registered consistently
            $essentialServices = [
                'Ardenexal\FHIRTools\FHIRModelGenerator',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector',
            ];

            foreach ($essentialServices as $serviceId) {
                self::assertTrue(
                    $container->hasDefinition($serviceId) || $container->hasAlias($serviceId),
                    "Service {$serviceId} should be registered consistently across Symfony versions",
                );
            }
        });
    }

    /**
     * Test that configuration validation works across Symfony versions.
     *
     * **Feature: repository-reorganization, Property 11: Configuration compatibility**
     * **Validates: Requirements 3.4**
     *
     * Property: Configuration validation should behave consistently
     * across different Symfony versions.
     */
    public function testConfigurationValidationCompatibility(): void
    {
        $this->forAll(
            Generator\elements(['INVALID', 'R3', 'R6', '', 'invalid-version']), // Invalid FHIR versions
        )->then(function(string $invalidVersion): void {
            $configuration = new Configuration();
            $processor     = new Processor();

            $config = [
                'default_version' => $invalidVersion,
            ];

            // Invalid configurations should be rejected consistently across Symfony versions
            $exceptionThrown = false;
            try {
                $processor->processConfiguration($configuration, [$config]);
            } catch (\Exception $e) {
                $exceptionThrown = true;
                // Verify that the error message is helpful
                self::assertStringContainsString('Invalid FHIR version', $e->getMessage());
            }

            self::assertTrue($exceptionThrown, "Invalid FHIR version '{$invalidVersion}' should be rejected");
        });
    }

    /**
     * Test that bundle extension alias works across Symfony versions.
     *
     * **Feature: repository-reorganization, Property 11: Configuration compatibility**
     * **Validates: Requirements 3.4**
     *
     * Property: Bundle extension alias should work consistently
     * across different Symfony versions.
     */
    public function testExtensionAliasCompatibility(): void
    {
        $extension = new FHIRExtension();

        // Extension alias should be consistent
        self::assertEquals('fhir', $extension->getAlias());

        // Test that the extension can be used with different configuration formats
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', '/tmp/test');
        $container->setParameter('kernel.cache_dir', '/tmp/test/cache');

        $configs = [
            // Empty config (should use defaults)
            [],

            // Config with just version
            ['default_version' => 'R4B'],

            // Config with validation settings
            [
                'validation' => [
                    'enabled'     => true,
                    'strict_mode' => false,
                ],
            ],
        ];

        foreach ($configs as $config) {
            // Should work without throwing exceptions
            $extension->load([$config], $container);

            // Verify that the extension processed the configuration
            self::assertTrue($container->hasParameter('fhir.default_version'));
            self::assertTrue($container->hasParameter('fhir.validation_enabled'));
            self::assertTrue($container->hasParameter('fhir.validation_strict_mode'));
        }
    }

    /**
     * Test that parameter resolution works across Symfony versions.
     *
     * **Feature: repository-reorganization, Property 11: Configuration compatibility**
     * **Validates: Requirements 3.4**
     *
     * Property: Parameter resolution should work consistently
     * across different Symfony versions.
     */
    public function testParameterResolutionCompatibility(): void
    {
        $container = new ContainerBuilder();
        $extension = new FHIRExtension();

        // Set up kernel parameters that should be available in all Symfony versions
        $container->setParameter('kernel.project_dir', '/app');
        $container->setParameter('kernel.cache_dir', '/app/var/cache');

        $config = [
            'output_directory' => '%kernel.project_dir%/output',
            'cache_directory'  => '%kernel.cache_dir%/fhir',
        ];

        $extension->load([$config], $container);

        // Verify that parameter resolution works
        self::assertEquals('%kernel.project_dir%/output', $container->getParameter('fhir.output_directory'));
        self::assertEquals('%kernel.cache_dir%/fhir', $container->getParameter('fhir.cache_directory'));

        // Test that Symfony version information is available
        $symfonyVersion = $container->getParameter('fhir.symfony_version');
        self::assertEquals(Kernel::VERSION, $symfonyVersion);

        // Verify that version flags are boolean
        self::assertIsBool($container->getParameter('fhir.is_symfony_64_or_higher'));
        self::assertIsBool($container->getParameter('fhir.is_symfony_70_or_higher'));
        self::assertIsBool($container->getParameter('fhir.is_symfony_74_or_higher'));
    }
}
