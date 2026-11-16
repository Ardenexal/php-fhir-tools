<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataCache;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\Generator;
use Eris\TestTrait;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Property-based tests for Serialization component performance.
 *
 * **Feature: repository-reorganization, Property 24: Serialization performance**
 *
 * Tests that the Serialization component maintains acceptable performance for large FHIR documents.
 *
 * Timings run on shared CI runners, so every measurement goes through fastestRunMs() and every
 * budget through assertWithinBudget(). Together they keep scheduler and garbage-collection noise
 * from failing the suite while still catching per-operation regressions.
 *
 * @author Ardenexal
 */
class SerializationPerformanceTest extends TestCase
{
    use TestTrait;

    /**
     * Fixed allowance added to every time budget, in milliseconds.
     *
     * A per-iteration budget alone is tiny when Eris picks a small iteration count (4 iterations
     * at 1ms each is a 4ms budget), so one scheduler pause or garbage-collection cycle on a shared
     * CI runner would fail the test even though nothing is slow. This allowance absorbs that fixed
     * noise. The per-iteration budgets still catch the regressions these tests guard against,
     * such as IO or an O(n) lookup inside a loop.
     */
    private const float FIXED_ALLOWANCE_MS = 50.0;

    /**
     * Timed passes per measurement. The fastest pass is reported.
     *
     * Noise only ever adds time, so the fastest of several passes is the closest to the real cost.
     * A genuine regression slows every pass and still fails.
     */
    private const int TIMED_PASSES = 3;

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

            // Create contexts multiple times to test performance
            $duration = self::fastestRunMs($iterations, static function() use ($contextFactory, $format): void {
                $context = $format === 'xml'
                    ? $contextFactory->createXmlContext()
                    : $contextFactory->createJsonContext();

                // Verify context is valid
                self::assertIsArray($context);
                self::assertArrayHasKey('format', $context);
            });

            // Allow 1ms per iteration on top of the fixed allowance
            self::assertWithinBudget($duration, $iterations, 1.0, 'Context creation');
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
            // Create and manipulate debug info multiple times
            $duration = self::fastestRunMs($iterations, static function(int $i) use ($format, $operation): void {
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
            });

            // Allow 0.5ms per iteration on top of the fixed allowance
            self::assertWithinBudget($duration, $iterations, 0.5, 'Debug info operations');
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

            // Test cache write performance. The cache is emptied before each timed pass so every
            // pass inserts new entries rather than overwriting the previous pass's.
            $writeDuration = self::fastestRunMs(
                $iterations,
                static function(int $i) use ($cache): void {
                    $className = "TestClass{$i}";
                    $cache->cacheFHIRTypeMetadata($className, "TestType{$i}");
                    $cache->cacheFHIRVersionMetadata($className, 'R4B');
                    $cache->cacheStructureKindFlag($className, 'resource', true);
                },
                $cache->invalidateCache(...),
            );

            // Test cache read performance
            $readDuration = self::fastestRunMs($iterations, static function(int $i) use ($cache): void {
                $className     = "TestClass{$i}";
                $fhirType      = $cache->getFHIRTypeMetadata($className);
                $fhirVersion   = $cache->getFHIRVersionMetadata($className);
                $structureType = $cache->getStructureKindFlag($className, 'resource');

                // Verify cached values
                self::assertSame("TestType{$i}", $fhirType);
                self::assertSame('R4B', $fhirVersion);
                self::assertTrue($structureType);
            });

            // Allow 5ms per write and 2ms per read, enough to catch accidental IO or O(n) lookups
            self::assertWithinBudget($writeDuration, $iterations, 5.0, 'Cache writes');
            self::assertWithinBudget($readDuration, $iterations, 2.0, 'Cache reads');

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
            // Not static: createStub() needs the test case instance
            $duration = self::fastestRunMs($iterations, function(): void {
                // Create stub serializer
                $serializer = $this->createStub(SerializerInterface::class);

                // Create components
                $contextFactory = new FHIRSerializationContextFactory();
                $debugInfo      = FHIRSerializationDebugInfo::forNormalization('json');

                // Create service
                $service = new FHIRSerializationService(
                    $serializer,
                    $contextFactory,
                    $debugInfo,
                );

                // Verify service was created
                self::assertInstanceOf(FHIRSerializationService::class, $service);
            });

            // Allow 10ms per instantiation, including stub creation
            self::assertWithinBudget($duration, $iterations, 10.0, 'Service instantiation');
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

    /**
     * Runs $operation $iterations times per pass and returns the fastest pass in milliseconds.
     *
     * One untimed call runs first so one-off costs, such as autoloading or PHPUnit generating a
     * test double class, are not charged to the timed passes.
     *
     * @param callable(int): void     $operation      receives the iteration index
     * @param (callable(): void)|null $beforeEachPass runs untimed before each timed pass, e.g. to reset state
     */
    private static function fastestRunMs(int $iterations, callable $operation, ?callable $beforeEachPass = null): float
    {
        $operation(0);

        $fastestNs = PHP_INT_MAX;
        for ($pass = 0; $pass < self::TIMED_PASSES; ++$pass) {
            if ($beforeEachPass !== null) {
                $beforeEachPass();
            }

            $startNs = hrtime(true);
            for ($i = 0; $i < $iterations; ++$i) {
                $operation($i);
            }
            $fastestNs = min($fastestNs, hrtime(true) - $startNs);
        }

        return $fastestNs / 1_000_000;
    }

    /**
     * Asserts $durationMs is under the fixed allowance plus $perIterationMs for each iteration.
     */
    private static function assertWithinBudget(float $durationMs, int $iterations, float $perIterationMs, string $label): void
    {
        $budgetMs = self::FIXED_ALLOWANCE_MS + ($iterations * $perIterationMs);

        self::assertLessThan(
            $budgetMs,
            $durationMs,
            sprintf('%s took %.2fms for %d iterations, expected < %.2fms', $label, $durationMs, $iterations, $budgetMs),
        );
    }
}
