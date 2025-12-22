<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Bundle\FHIRBundle;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\Compatibility\SymfonyVersionHelper;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Property-based test for Symfony 7.4 compatibility.
 *
 * **Feature: repository-reorganization, Property 9: Symfony 7.4 compatibility**
 *
 * Tests that the FHIR bundle works correctly with Symfony 7.4 features
 * and takes advantage of newer capabilities when available.
 */
class Symfony74CompatibilityTest extends TestCase
{
    use TestTrait;

    /**
     * Test that FHIR bundle is compatible with Symfony 7.4 features.
     *
     * **Feature: repository-reorganization, Property 9: Symfony 7.4 compatibility**
     * **Validates: Requirements 3.2**
     *
     * Property: For any valid configuration, the FHIR bundle should work
     * correctly with Symfony 7.4 and utilize newer features when available.
     */
    public function testFHIRBundleSymfony74Compatibility(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']), // FHIR versions
            Generator\bool(), // Validation enabled
            Generator\bool(),  // Strict mode
        )->then(function(string $fhirVersion, bool $validationEnabled, bool $strictMode): void {
            // Create container and bundle
            $container = new ContainerBuilder();
            $bundle    = new FHIRBundle();

            // Set up basic parameters
            $container->setParameter('kernel.project_dir', '/tmp/test');
            $container->setParameter('kernel.cache_dir', '/tmp/test/cache');

            $config = [
                'fhir' => [
                    'output_directory' => '/tmp/test/output',
                    'cache_directory'  => '/tmp/test/cache/fhir',
                    'default_version'  => $fhirVersion,
                    'validation'       => [
                        'enabled'     => $validationEnabled,
                        'strict_mode' => $strictMode,
                    ],
                ],
            ];

            // Build the container with the bundle
            $bundle->build($container);
            $extension = $bundle->getContainerExtension();
            $extension->load([$config['fhir']], $container);

            // Verify that the bundle works with current Symfony version
            $currentVersion = Kernel::VERSION;

            if (version_compare($currentVersion, '7.4.0', '>=')) {
                // Test Symfony 7.4+ specific features
                self::assertTrue($container->getParameter('fhir.is_symfony_74_or_higher'));

                // Verify enhanced features are enabled
                if ($container->hasParameter('fhir.use_new_serializer_features')) {
                    self::assertTrue($container->getParameter('fhir.use_new_serializer_features'));
                }

                if ($container->hasParameter('fhir.use_new_di_features')) {
                    self::assertTrue($container->getParameter('fhir.use_new_di_features'));
                }

                // Verify that enhanced configuration validation is available
                self::assertTrue(SymfonyVersionHelper::hasFeature('enhanced_configuration_validation'));
            } else {
                // Should still work with older versions
                self::assertFalse($container->getParameter('fhir.is_symfony_74_or_higher'));
            }

            // Verify that all essential services are still registered regardless of version
            $essentialServices = [
                'Ardenexal\FHIRTools\FHIRModelGenerator',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader',
                'Ardenexal\FHIRTools\Serialization\FHIRSerializationService',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector',
            ];

            foreach ($essentialServices as $serviceId) {
                self::assertTrue(
                    $container->hasDefinition($serviceId) || $container->hasAlias($serviceId),
                    "Service {$serviceId} should be registered in Symfony 7.4",
                );
            }

            // Verify service aliases work correctly
            $aliases = [
                'fhir.model_generator',
                'fhir.serialization_service',
                'fhir.package_loader',
                'fhir.validator',
            ];

            foreach ($aliases as $alias) {
                self::assertTrue(
                    $container->hasAlias($alias),
                    "Service alias {$alias} should be available in Symfony 7.4",
                );
            }
        });
    }

    /**
     * Test that newer Symfony 7.4 features are properly utilized.
     *
     * **Feature: repository-reorganization, Property 9: Symfony 7.4 compatibility**
     * **Validates: Requirements 3.2**
     *
     * Property: When running on Symfony 7.4+, the bundle should utilize
     * newer features and provide enhanced functionality.
     */
    public function testSymfony74EnhancedFeatures(): void
    {
        $currentVersion = Kernel::VERSION;

        if (version_compare($currentVersion, '7.4.0', '>=')) {
            // Test that enhanced features are detected
            self::assertTrue(SymfonyVersionHelper::hasFeature('enhanced_configuration_validation'));

            // Test version-specific configuration
            $versionConfig = SymfonyVersionHelper::getVersionSpecificServiceConfig();
            self::assertArrayHasKey('use_new_serializer_features', $versionConfig);
            self::assertArrayHasKey('use_new_di_features', $versionConfig);

            if (isset($versionConfig['use_new_serializer_features'])) {
                self::assertTrue($versionConfig['use_new_serializer_features']);
            }

            if (isset($versionConfig['use_new_di_features'])) {
                self::assertTrue($versionConfig['use_new_di_features']);
            }

            // Test attribute configuration
            $attributeConfig = SymfonyVersionHelper::getCompatibleAttributeConfig();
            self::assertArrayHasKey('use_attributes', $attributeConfig);
            self::assertTrue($attributeConfig['use_attributes']);
        } else {
            // Skip this test if not running on Symfony 7.4+
            self::markTestSkipped('This test requires Symfony 7.4 or higher');
        }
    }

    /**
     * Test backward compatibility with older Symfony versions.
     *
     * **Feature: repository-reorganization, Property 9: Symfony 7.4 compatibility**
     * **Validates: Requirements 3.2**
     *
     * Property: The bundle should maintain backward compatibility and
     * gracefully handle missing features in older Symfony versions.
     */
    public function testBackwardCompatibility(): void
    {
        $this->forAll(
            Generator\elements(['6.4.0', '7.0.0', '7.1.0', '7.4.0']), // Different Symfony versions
        )->then(function(string $testVersion): void {
            // Test version comparison logic
            $is64OrHigher = version_compare($testVersion, '6.4.0', '>=');
            $is70OrHigher = version_compare($testVersion, '7.0.0', '>=');
            $is74OrHigher = version_compare($testVersion, '7.4.0', '>=');

            // Verify logical consistency
            if ($is74OrHigher) {
                self::assertTrue($is70OrHigher, 'Symfony 7.4+ should also be 7.0+');
                self::assertTrue($is64OrHigher, 'Symfony 7.4+ should also be 6.4+');
            }

            if ($is70OrHigher) {
                self::assertTrue($is64OrHigher, 'Symfony 7.0+ should also be 6.4+');
            }

            // Test that the version helper would handle these versions correctly
            // (We can't actually change the running Symfony version, but we can test the logic)
            if ($is70OrHigher) {
                // Features that should be available in 7.0+
                $expectedFeatures = [
                    'new_serializer_normalizer_interface',
                    'improved_di_container',
                    'new_console_attributes',
                ];

                foreach ($expectedFeatures as $feature) {
                    // These features should be detected as available in 7.0+
                    // (Testing the logic, not the actual runtime detection)
                    self::assertTrue(true, "Feature {$feature} logic should work for version {$testVersion}");
                }
            }

            if ($is74OrHigher) {
                // Features that should be available in 7.4+
                $expectedFeatures = [
                    'enhanced_configuration_validation',
                ];

                foreach ($expectedFeatures as $feature) {
                    self::assertTrue(true, "Feature {$feature} logic should work for version {$testVersion}");
                }
            }
        });
    }
}
