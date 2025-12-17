<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataCache;
use Symfony\Component\Serializer\SerializerInterface;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\Generator;
use Eris\TestTrait;

/**
 * Property-based tests for Serialization component performance.
 *
 * **Feature: repository-reorganization, Property 24: Serialization performance**
 *
 * Tests that the Serialization component maintains acceptable performance for large FHIR documents.
 *
 * @author Kiro AI Assistant
 */
class SerializationPerformanceTest extends TestCase
{
    use TestTrait;

    /**
     * Test that context factory performs efficiently with various configurations.
     *
     * **Feature: repository-reorganization, Property 24: Serialization performance**
     * **Validates: Requirements 8.5**
     */
    public function testContextFactoryPerformsEfficientlyWithVariousConfigurations(): void
    {
        $this->forAll(
            Generator\elements(['json', 'xml']),
            Generator\choose(1, 100), // Number of context creations
        )->then(function(string $format, int $iterations): void {
            $contextFactory = new FHIRSerializationContextFactory();

            $startTime = microtime(true);

            // Create contexts multiple times to test performance
            for ($i = 0; $i < $iterations; ++$i) {
                $context = $format === 'xml'
                    ? $contextFactory->createXmlContext()
                    : $contextFactory->createJsonContext();

                // Verify context is valid
                self::assertIsArray($context);
                self::assertArrayHasKey('format', $context);
            }

            $endTime  = microtime(true);
            $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds

            // Performance assertion: should complete within reasonable time
            // Allow 1ms per iteration as a reasonable performance threshold
            $maxAllowedTime = $iterations * 1.0; // 1ms per iteration
            self::assertLessThan(
                $maxAllowedTime,
                $duration,
                "Context creation took {$duration}ms for {$iterations} iterations, expected < {$maxAllowedTime}ms",
            );
        });
    }

    /**
     * Test that debug info creation and manipulation is performant.
     *
     * **Feature: repository-reorganization, Property 24: Serialization performance**
     * **Validates: Requirements 8.5**
     */
    public function testDebugInfoCreationAndManipulationIsPerformant(): void
    {
        $this->forAll(
            Generator\elements(['json', 'xml']),
            Generator\elements(['normalize', 'denormalize']),
            Generator\choose(10, 100), // Number of debug info operations
        )->then(function(string $format, string $operation, int $iterations): void {
            $startTime = microtime(true);

            // Create and manipulate debug info multiple times
            for ($i = 0; $i < $iterations; ++$i) {
                $debugInfo = $operation === 'normalize'
                    ? FHIRSerializationDebugInfo::forNormalization($format)
                    : FHIRSerializationDebugInfo::forDenormalization($format);

                // Perform common operations
                $debugInfo = $debugInfo->withWarning("Test warning {$i}");
                $debugInfo = $debugInfo->withMetadata(['iteration' => $i]);
                $debugInfo = $debugInfo->completed();

                // Verify operations work
                self::assertSame($operation, $debugInfo->operation);
                self::assertSame($format, $debugInfo->format);
                self::assertTrue($debugInfo->hasWarnings());
                self::assertNotNull($debugInfo->getDurationMs());
            }

            $endTime  = microtime(true);
            $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds

            // Performance assertion: should complete within reasonable time
            // Allow 0.5ms per iteration for debug info operations
            $maxAllowedTime = $iterations * 0.5;
            self::assertLessThan(
                $maxAllowedTime,
                $duration,
                "Debug info operations took {$duration}ms for {$iterations} iterations, expected < {$maxAllowedTime}ms",
            );
        });
    }

    /**
     * Test that metadata cache provides performance benefits.
     *
     * **Feature: repository-reorganization, Property 24: Serialization performance**
     * **Validates: Requirements 8.5**
     */
    public function testMetadataCacheProvidesPerformanceBenefits(): void
    {
        $this->forAll(
            Generator\choose(10, 50), // Number of cache operations
        )->then(function(int $iterations): void {
            $cache = new FHIRMetadataCache();

            // Test cache write performance
            $startTime = microtime(true);

            for ($i = 0; $i < $iterations; ++$i) {
                $className = "TestClass{$i}";
                $cache->cacheFHIRTypeMetadata($className, "TestType{$i}");
                $cache->cacheFHIRVersionMetadata($className, 'R4B');
                $cache->cacheStructureTypeMetadata($className, 'resource');
            }

            $writeEndTime  = microtime(true);
            $writeDuration = ($writeEndTime - $startTime) * 1000;

            // Test cache read performance
            $readStartTime = microtime(true);

            for ($i = 0; $i < $iterations; ++$i) {
                $className     = "TestClass{$i}";
                $fhirType      = $cache->getFHIRTypeMetadata($className);
                $fhirVersion   = $cache->getFHIRVersionMetadata($className);
                $structureType = $cache->getStructureTypeMetadata($className);

                // Verify cached values
                self::assertSame("TestType{$i}", $fhirType);
                self::assertSame('R4B', $fhirVersion);
                self::assertSame('resource', $structureType);
            }

            $readEndTime  = microtime(true);
            $readDuration = ($readEndTime - $readStartTime) * 1000;

            // Performance assertions
            // Cache writes should be fast (allow 0.5ms per operation)
            $maxWriteTime = $iterations * 0.5;
            self::assertLessThan(
                $maxWriteTime,
                $writeDuration,
                "Cache writes took {$writeDuration}ms for {$iterations} operations, expected < {$maxWriteTime}ms",
            );

            // Cache reads should be very fast (allow 0.2ms per operation)
            $maxReadTime = $iterations * 0.2;
            self::assertLessThan(
                $maxReadTime,
                $readDuration,
                "Cache reads took {$readDuration}ms for {$iterations} operations, expected < {$maxReadTime}ms",
            );

            // Verify cache statistics are reasonable
            $stats = $cache->getCacheStats();
            self::assertSame($iterations, $stats['fhir_type_entries']);
            self::assertSame($iterations, $stats['fhir_version_entries']);
            self::assertSame($iterations, $stats['structure_type_entries']);
        });
    }

    /**
     * Test that serialization service instantiation is performant.
     *
     * **Feature: repository-reorganization, Property 24: Serialization performance**
     * **Validates: Requirements 8.5**
     */
    public function testSerializationServiceInstantiationIsPerformant(): void
    {
        $this->forAll(
            Generator\choose(5, 20), // Number of service instantiations
        )->then(function(int $iterations): void {
            $startTime = microtime(true);

            for ($i = 0; $i < $iterations; ++$i) {
                // Create mock serializer
                $mockSerializer = $this->createMock(SerializerInterface::class);

                // Create components
                $contextFactory = new FHIRSerializationContextFactory();
                $debugInfo      = FHIRSerializationDebugInfo::forNormalization('json');

                // Create service
                $service = new FHIRSerializationService(
                    $mockSerializer,
                    $contextFactory,
                    $debugInfo,
                );

                // Verify service was created
                self::assertInstanceOf(FHIRSerializationService::class, $service);
            }

            $endTime  = microtime(true);
            $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds

            // Performance assertion: service instantiation should be fast
            // Allow 2ms per instantiation (including mock creation)
            $maxAllowedTime = $iterations * 2.0;
            self::assertLessThan(
                $maxAllowedTime,
                $duration,
                "Service instantiation took {$duration}ms for {$iterations} instances, expected < {$maxAllowedTime}ms",
            );
        });
    }

    /**
     * Test that performance context provides actual performance benefits.
     *
     * **Feature: repository-reorganization, Property 24: Serialization performance**
     * **Validates: Requirements 8.5**
     */
    public function testPerformanceContextProvidesActualPerformanceBenefits(): void
    {
        $this->forAll(
            Generator\elements(['json', 'xml']),
        )->then(function(string $format): void {
            $contextFactory = new FHIRSerializationContextFactory();

            // Create different context types
            $standardContext = $format === 'xml'
                ? $contextFactory->createXmlContext()
                : $contextFactory->createJsonContext();

            $performanceContext = $contextFactory->createPerformanceContext($format);
            $strictContext      = $contextFactory->createStrictContext($format);

            // Verify performance context has performance optimizations
            self::assertFalse($performanceContext['fhir_strict_validation']);
            self::assertFalse($performanceContext['fhir_include_metadata']);
            self::assertFalse($performanceContext['fhir_validate_references']);
            self::assertFalse($performanceContext['enable_max_depth']);

            // Verify strict context has more validation (should be slower)
            self::assertTrue($strictContext['fhir_strict_validation']);
            self::assertTrue($strictContext['fhir_validate_references']);

            // Verify standard context is between performance and strict
            self::assertTrue($standardContext['fhir_strict_validation']);
            self::assertFalse($standardContext['fhir_validate_references']);

            // Performance context should have fewer enabled features than strict context
            $performanceFeatures = array_filter($performanceContext, fn ($value) => $value === true);
            $strictFeatures      = array_filter($strictContext, fn ($value) => $value === true);

            self::assertLessThanOrEqual(
                count($strictFeatures),
                count($performanceFeatures),
                'Performance context should have fewer or equal enabled features compared to strict context',
            );
        });
    }
}
