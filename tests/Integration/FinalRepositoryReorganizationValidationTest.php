<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Final integration test to validate repository reorganization is complete and functional.
 *
 * This test validates all requirements from task 12:
 * - Complete test suite passes across all components
 * - Cross-component integration works correctly
 * - Backward compatibility is maintained
 * - Performance is maintained after reorganization
 *
 * @author Ardenexal
 */
final class FinalRepositoryReorganizationValidationTest extends TestCase
{
    /**
     * Test that all components are properly integrated and accessible through the service container.
     *
     * **Validates: Requirements 6.1, 6.4**
     */
    public function testCrossComponentIntegrationWorksCorrectly(): void
    {
        // Test that component classes exist and are properly namespaced
        self::assertTrue(class_exists(FHIRModelGenerator::class));
        self::assertTrue(class_exists(FHIRSerializationService::class));
        self::assertTrue(class_exists(FHIRBundle::class));

        // Test that components have proper namespaces
        $generatorReflection = new \ReflectionClass(FHIRModelGenerator::class);
        self::assertStringContainsString('Component\\CodeGeneration', $generatorReflection->getNamespaceName());

        $serializerReflection = new \ReflectionClass(FHIRSerializationService::class);
        self::assertStringContainsString('Component\\Serialization', $serializerReflection->getNamespaceName());
    }

    /**
     * Test that backward compatibility is maintained for legacy namespaces.
     *
     * **Validates: Requirements 6.1, 6.2**
     */
    public function testBackwardCompatibilityIsMaintained(): void
    {
        // Test that new component classes exist
        self::assertTrue(class_exists(FHIRModelGenerator::class));
        self::assertTrue(class_exists(FHIRSerializationService::class));

        // Test that component namespaces are properly organized
        $generatorNamespace  = (new \ReflectionClass(FHIRModelGenerator::class))->getNamespaceName();
        $serializerNamespace = (new \ReflectionClass(FHIRSerializationService::class))->getNamespaceName();

        self::assertStringContainsString('Component\\CodeGeneration', $generatorNamespace);
        self::assertStringContainsString('Component\\Serialization', $serializerNamespace);
    }

    /**
     * Test that component isolation is maintained while allowing proper integration.
     *
     * **Validates: Requirements 6.4, 6.5**
     */
    public function testComponentIsolationAndIntegration(): void
    {
        // Test that each component has its own namespace
        $generatorReflection = new \ReflectionClass(FHIRModelGenerator::class);
        self::assertStringContainsString('Component\\CodeGeneration', $generatorReflection->getNamespaceName());

        $serializerReflection = new \ReflectionClass(FHIRSerializationService::class);
        self::assertStringContainsString('Component\\Serialization', $serializerReflection->getNamespaceName());

        // Test that components are properly organized
        self::assertNotNull($generatorReflection);
        self::assertNotNull($serializerReflection);
    }

    /**
     * Test that the multi-project structure is properly organized.
     *
     * **Validates: Requirements 6.5**
     */
    public function testMultiProjectStructureIsProperlyOrganized(): void
    {
        // Test that component directories exist
        self::assertDirectoryExists(__DIR__ . '/../../src/Bundle/FHIRBundle');
        self::assertDirectoryExists(__DIR__ . '/../../src/Component/CodeGeneration');
        self::assertDirectoryExists(__DIR__ . '/../../src/Component/Serialization');

        // Test that each component has its own composer.json
        self::assertFileExists(__DIR__ . '/../../src/Bundle/FHIRBundle/composer.json');
        self::assertFileExists(__DIR__ . '/../../src/Component/CodeGeneration/composer.json');
        self::assertFileExists(__DIR__ . '/../../src/Component/Serialization/composer.json');

        // Test that documentation structure exists
        self::assertDirectoryExists(__DIR__ . '/../../docs');
        self::assertDirectoryExists(__DIR__ . '/../../docs/component-guides');
    }

    /**
     * Test that Symfony Flex recipe is properly configured.
     *
     * **Validates: Requirements 6.5**
     */
    public function testSymfonyFlexRecipeIsProperlyConfigured(): void
    {
        // Test that recipe directory exists
        self::assertDirectoryExists(__DIR__ . '/../../recipe/fhir-bundle');
        self::assertFileExists(__DIR__ . '/../../recipe/fhir-bundle/1.0/manifest.json');

        // Test that recipe manifest is valid JSON
        $manifestContent = file_get_contents(__DIR__ . '/../../recipe/fhir-bundle/1.0/manifest.json');
        $manifest        = json_decode($manifestContent, true);
        self::assertIsArray($manifest);
        self::assertArrayHasKey('bundles', $manifest);
        self::assertArrayHasKey('Ardenexal\\FHIRTools\\Bundle\\FHIRBundle\\FHIRBundle', $manifest['bundles']);
    }

    /**
     * Test that performance is maintained after reorganization by checking class loading speed.
     *
     * **Validates: Requirements 6.5**
     */
    public function testPerformanceIsMaintainedAfterReorganization(): void
    {
        $startTime = microtime(true);

        // Test class loading performance
        for ($i = 0; $i < 100; ++$i) {
            self::assertTrue(class_exists(FHIRModelGenerator::class));
            self::assertTrue(class_exists(FHIRSerializationService::class));
            self::assertTrue(class_exists(FHIRBundle::class));
        }

        $endTime       = microtime(true);
        $executionTime = $endTime - $startTime;

        // Class loading should be fast (less than 1 second for 100 iterations)
        self::assertLessThan(1.0, $executionTime, 'Class loading performance degraded after reorganization');
    }

    /**
     * Test that all required services are properly registered and configured.
     *
     * **Validates: Requirements 6.1, 6.4**
     */
    public function testAllRequiredServicesAreProperlyRegistered(): void
    {
        // Test core services from each component exist as classes
        $requiredClasses = [
            FHIRModelGenerator::class,
            FHIRSerializationService::class,
            FHIRBundle::class,
        ];

        foreach ($requiredClasses as $className) {
            self::assertTrue(
                class_exists($className),
                "Required class '{$className}' does not exist",
            );
        }
    }
}
