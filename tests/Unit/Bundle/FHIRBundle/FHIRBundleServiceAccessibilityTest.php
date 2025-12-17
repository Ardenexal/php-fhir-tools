<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Bundle\FHIRBundle;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Property-based test for FHIR Bundle service container accessibility.
 *
 * **Feature: repository-reorganization, Property 6: Service container accessibility**
 *
 * Tests that FHIR services registered by the bundle are properly accessible
 * through the service container, ensuring public services can be retrieved
 * and private services are properly encapsulated.
 */
class FHIRBundleServiceAccessibilityTest extends TestCase
{
    use TestTrait;

    /**
     * Test that public FHIR services are accessible from the container.
     *
     * **Feature: repository-reorganization, Property 6: Service container accessibility**
     * **Validates: Requirements 2.4**
     *
     * Property: For any valid bundle configuration, all public FHIR services
     * should be accessible through the service container and service aliases.
     */
    public function testPublicFHIRServicesAreAccessible(): void
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

            // Verify public services are marked as public
            $publicServices = [
                'Ardenexal\FHIRTools\FHIRModelGenerator',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader',
                'Ardenexal\FHIRTools\Serialization\FHIRSerializationService',
                'Ardenexal\FHIRTools\Serialization\FHIRValidator',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Package\RetryHandler',
            ];

            foreach ($publicServices as $serviceId) {
                if ($container->hasDefinition($serviceId)) {
                    $definition = $container->getDefinition($serviceId);
                    self::assertTrue(
                        $definition->isPublic(),
                        "Service {$serviceId} should be marked as public",
                    );
                }
            }

            // Verify service aliases are public and accessible
            $publicAliases = [
                'fhir.model_generator',
                'fhir.serialization_service',
                'fhir.package_loader',
                'fhir.validator',
            ];

            foreach ($publicAliases as $alias) {
                if ($container->hasAlias($alias)) {
                    $aliasDefinition = $container->getAlias($alias);
                    self::assertTrue(
                        $aliasDefinition->isPublic(),
                        "Service alias {$alias} should be marked as public",
                    );
                }
            }

            // Verify that services have proper autowiring configuration
            $autowiredServices = [
                'Ardenexal\FHIRTools\BuilderContext',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector',
                'Ardenexal\FHIRTools\Component\CodeGeneration\Package\RetryHandler',
            ];

            foreach ($autowiredServices as $serviceId) {
                if ($container->hasDefinition($serviceId)) {
                    $definition = $container->getDefinition($serviceId);
                    self::assertTrue(
                        $definition->isAutowired(),
                        "Service {$serviceId} should be autowired",
                    );
                }
            }

            // Verify that normalizers are properly tagged
            $normalizers = [
                'Ardenexal\FHIRTools\Serialization\FHIRResourceNormalizer',
                'Ardenexal\FHIRTools\Serialization\FHIRComplexTypeNormalizer',
                'Ardenexal\FHIRTools\Serialization\FHIRPrimitiveTypeNormalizer',
                'Ardenexal\FHIRTools\Serialization\FHIRBackboneElementNormalizer',
            ];

            foreach ($normalizers as $normalizerId) {
                if ($container->hasDefinition($normalizerId)) {
                    $definition = $container->getDefinition($normalizerId);
                    $tags       = $definition->getTags();
                    self::assertArrayHasKey(
                        'serializer.normalizer',
                        $tags,
                        "Normalizer {$normalizerId} should be tagged as serializer.normalizer",
                    );
                }
            }
        });
    }
}
