<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Bundle\FHIRBundle;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator;
use Ardenexal\FHIRTools\FHIRModelGenerator;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Property-based test for FHIR Bundle service registration.
 *
 * **Feature: repository-reorganization, Property 5: Bundle service registration**
 *
 * Tests that all FHIR services are properly registered in the Symfony service container
 * when the FHIRBundle is loaded, ensuring services are accessible and properly configured.
 */
class FHIRBundleServiceRegistrationTest extends TestCase
{
    use TestTrait;

    /**
     * Test that FHIR services are properly registered in the container.
     *
     * **Feature: repository-reorganization, Property 5: Bundle service registration**
     * **Validates: Requirements 2.3**
     *
     * Property: For any valid bundle configuration, all essential FHIR services
     * should be registered and accessible in the service container.
     */
    public function testFHIRServicesAreRegisteredInContainer(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']), // FHIR versions
            Generator\bool(), // Validation enabled
            Generator\bool(),  // Strict mode
        )->then(function(string $fhirVersion, bool $validationEnabled, bool $strictMode): void {
            // Create container and bundle
            $container = new ContainerBuilder();
            $bundle    = new FHIRBundle();

            // Set up basic parameters that the bundle expects
            $container->setParameter('kernel.project_dir', '/tmp/test');
            $container->setParameter('kernel.cache_dir', '/tmp/test/cache');

            // Configure the bundle with test parameters
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

            // Verify essential FHIR services are registered (before compilation)
            $essentialServices = [
                'Ardenexal\FHIRTools\FHIRModelGenerator',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader',
                'Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService',
                'Ardenexal\FHIRTools\Component\Serialization\Validator\FHIRValidator',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Package\RetryHandler',
            ];

            foreach ($essentialServices as $serviceId) {
                self::assertTrue(
                    $container->hasDefinition($serviceId) || $container->hasAlias($serviceId),
                    "Service {$serviceId} should be registered in the container",
                );
            }

            // Verify service aliases are properly configured
            $aliases = [
                'fhir.model_generator'       => FHIRModelGenerator::class,
                'fhir.serialization_service' => FHIRSerializationService::class,
                'fhir.package_loader'        => PackageLoader::class,
                'fhir.validator'             => FHIRValidator::class,
            ];

            foreach ($aliases as $alias => $target) {
                self::assertTrue(
                    $container->hasAlias($alias),
                    "Service alias {$alias} should be registered",
                );

                if ($container->hasAlias($alias)) {
                    self::assertEquals(
                        $target,
                        (string) $container->getAlias($alias),
                        "Service alias {$alias} should point to {$target}",
                    );
                }
            }

            // Verify configuration parameters are set correctly
            self::assertEquals($fhirVersion, $container->getParameter('fhir.default_version'));
            self::assertEquals($validationEnabled, $container->getParameter('fhir.validation_enabled'));
            self::assertEquals($strictMode, $container->getParameter('fhir.validation_strict_mode'));
        });
    }
}
