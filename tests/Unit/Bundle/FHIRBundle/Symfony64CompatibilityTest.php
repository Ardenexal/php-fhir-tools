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
 * Property-based test for Symfony 6.4 compatibility.
 *
 * **Feature: repository-reorganization, Property 8: Symfony 6.4 compatibility**
 *
 * Tests that the FHIR bundle works correctly with Symfony 6.4 features
 * and handles version-specific differences gracefully.
 */
class Symfony64CompatibilityTest extends TestCase
{
    use TestTrait;

    /**
     * Test that FHIR bundle is compatible with Symfony 6.4 features.
     *
     * **Feature: repository-reorganization, Property 8: Symfony 6.4 compatibility**
     * **Validates: Requirements 3.1**
     *
     * Property: For any valid configuration, the FHIR bundle should work
     * correctly with Symfony 6.4 and handle version-specific features appropriately.
     */
    public function testFHIRBundleSymfony64Compatibility(): void
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

            // Verify version-specific parameters are set
            self::assertTrue($container->hasParameter('fhir.symfony_version'));
            self::assertTrue($container->hasParameter('fhir.is_symfony_64_or_higher'));
            self::assertTrue($container->hasParameter('fhir.is_symfony_70_or_higher'));
            self::assertTrue($container->hasParameter('fhir.is_symfony_74_or_higher'));

            // Verify version detection works correctly
            $detectedVersion = $container->getParameter('fhir.symfony_version');
            self::assertEquals(Kernel::VERSION, $detectedVersion);

            // Verify compatibility flags are set correctly
            $is64OrHigher = $container->getParameter('fhir.is_symfony_64_or_higher');
            $is70OrHigher = $container->getParameter('fhir.is_symfony_70_or_higher');
            $is74OrHigher = $container->getParameter('fhir.is_symfony_74_or_higher');

            self::assertEquals(SymfonyVersionHelper::isSymfony64OrHigher(), $is64OrHigher);
            self::assertEquals(SymfonyVersionHelper::isSymfony70OrHigher(), $is70OrHigher);
            self::assertEquals(SymfonyVersionHelper::isSymfony74OrHigher(), $is74OrHigher);

            // Verify version-specific service configuration is applied
            if (version_compare(Kernel::VERSION, '6.4.0', '>=')) {
                // Should work with Symfony 6.4+ features
                self::assertTrue($container->hasParameter('fhir.use_new_serializer_features'));
                self::assertTrue($container->hasParameter('fhir.use_new_di_features'));

                // Verify that essential services are still registered
                $essentialServices = [
                    'Ardenexal\FHIRTools\FHIRModelGenerator',
                    'Ardenexal\FHIRTools\PackageLoader',
                    'Ardenexal\FHIRTools\ErrorCollector',
                ];

                foreach ($essentialServices as $serviceId) {
                    self::assertTrue(
                        $container->hasDefinition($serviceId) || $container->hasAlias($serviceId),
                        "Service {$serviceId} should be registered even in Symfony 6.4",
                    );
                }
            }
        });
    }

    /**
     * Test that version helper correctly identifies Symfony versions.
     *
     * **Feature: repository-reorganization, Property 8: Symfony 6.4 compatibility**
     * **Validates: Requirements 3.1**
     *
     * Property: The version helper should correctly identify Symfony versions
     * and provide accurate compatibility information.
     */
    public function testVersionHelperAccuracy(): void
    {
        // Test version detection
        $version = SymfonyVersionHelper::getSymfonyVersion();
        self::assertEquals(Kernel::VERSION, $version);

        // Test version comparison logic
        $currentVersion = Kernel::VERSION;

        if (version_compare($currentVersion, '6.4.0', '>=')) {
            self::assertTrue(SymfonyVersionHelper::isSymfony64OrHigher());
        } else {
            self::assertFalse(SymfonyVersionHelper::isSymfony64OrHigher());
        }

        if (version_compare($currentVersion, '7.0.0', '>=')) {
            self::assertTrue(SymfonyVersionHelper::isSymfony70OrHigher());
        } else {
            self::assertFalse(SymfonyVersionHelper::isSymfony70OrHigher());
        }

        if (version_compare($currentVersion, '7.4.0', '>=')) {
            self::assertTrue(SymfonyVersionHelper::isSymfony74OrHigher());
        } else {
            self::assertFalse(SymfonyVersionHelper::isSymfony74OrHigher());
        }
    }

    /**
     * Test that feature detection works correctly.
     *
     * **Feature: repository-reorganization, Property 8: Symfony 6.4 compatibility**
     * **Validates: Requirements 3.1**
     *
     * Property: Feature detection should accurately report availability
     * of version-specific Symfony features.
     */
    public function testFeatureDetection(): void
    {
        $this->forAll(
            Generator\elements([
                'new_serializer_normalizer_interface',
                'improved_di_container',
                'enhanced_configuration_validation',
                'new_console_attributes',
                'nonexistent_feature',
            ]),
        )->then(function(string $feature): void {
            $hasFeature = SymfonyVersionHelper::hasFeature($feature);

            // Verify feature detection is consistent
            if ($feature === 'nonexistent_feature') {
                self::assertFalse($hasFeature, 'Nonexistent features should return false');
            } else {
                // Feature availability should be consistent with version checks
                $currentVersion = Kernel::VERSION;

                switch ($feature) {
                    case 'new_serializer_normalizer_interface':
                    case 'improved_di_container':
                    case 'new_console_attributes':
                        $expected = version_compare($currentVersion, '7.0.0', '>=');
                        break;
                    case 'enhanced_configuration_validation':
                        $expected = version_compare($currentVersion, '7.4.0', '>=');
                        break;
                    default:
                        $expected = false;
                }

                self::assertEquals($expected, $hasFeature, "Feature {$feature} detection should match version");
            }
        });
    }
}
