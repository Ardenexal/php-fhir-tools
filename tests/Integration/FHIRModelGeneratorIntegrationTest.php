<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\ErrorCollector;
use Ardenexal\FHIRTools\FHIRModelGenerator;
use Ardenexal\FHIRTools\PackageLoader;
use Ardenexal\FHIRTools\RetryHandler;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Integration tests for FHIR model generation workflow
 *
 * This test class verifies the complete FHIR model generation process
 * from StructureDefinition loading through PHP class generation.
 *
 * Test Coverage:
 * - End-to-end generation workflow
 * - File system integration
 * - Error handling across components
 * - Generated code validation
 * - Performance characteristics
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class FHIRModelGeneratorIntegrationTest extends TestCase
{
    private FHIRModelGenerator $generator;

    private ErrorCollector $errorCollector;

    private PackageLoader $packageLoader;

    private RetryHandler $retryHandler;

    private Filesystem $filesystem;

    private string $tempOutputDir;

    protected function setUp(): void
    {
        $this->errorCollector = new ErrorCollector();
        $this->retryHandler   = new RetryHandler();
        $this->packageLoader  = new PackageLoader($this->retryHandler, $this->errorCollector);
        $this->filesystem     = new Filesystem();

        // Create temporary output directory
        $this->tempOutputDir = $this->createTempDirectory();

        $this->generator = new FHIRModelGenerator(
            $this->errorCollector,
            $this->packageLoader,
            $this->filesystem,
        );
    }

    protected function tearDown(): void
    {
        $this->cleanupTempDirectory($this->tempOutputDir);
    }

    /**
     * Test complete generation workflow with valid StructureDefinition
     */
    public function testCompleteGenerationWorkflow(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('Patient.json');

        $result = $this->generator->generateFromStructureDefinition(
            $structureDefinition,
            $this->tempOutputDir,
        );

        self::assertTrue($result, 'Generation should succeed for valid StructureDefinition');
        self::assertFalse($this->errorCollector->hasErrors(), 'No errors should occur during generation');

        // Verify generated files exist
        $expectedFile = $this->tempOutputDir . '/TestPatient.php';
        self::assertFileExists($expectedFile, 'Generated PHP class file should exist');

        // Verify generated code quality
        $generatedCode = file_get_contents($expectedFile);
        if ($generatedCode === false) {
            throw new \RuntimeException("Failed to read generated file: {$expectedFile}");
        }
        $this->assertValidPhpCode($generatedCode);
        $this->assertUsesStrictTypes($generatedCode);
        $this->assertContainsPhpDoc($generatedCode, ['author', 'since']);
    }

    /**
     * Test generation with invalid StructureDefinition
     */
    public function testGenerationWithInvalidStructureDefinition(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('InvalidStructureDefinition.json');

        $result = $this->generator->generateFromStructureDefinition(
            $structureDefinition,
            $this->tempOutputDir,
        );

        self::assertFalse($result, 'Generation should fail for invalid StructureDefinition');
        self::assertTrue($this->errorCollector->hasErrors(), 'Errors should be collected during validation');

        // Verify specific error types
        $this->assertErrorCollectorContains(
            $this->errorCollector,
            'Invalid cardinality',
            'Patient.invalidElement',
        );
    }

    /**
     * Test batch generation of multiple StructureDefinitions
     */
    public function testBatchGeneration(): void
    {
        $structureDefinitions = [
            $this->loadTestStructureDefinition('Patient.json'),
            $this->loadTestStructureDefinition('Observation.json'),
        ];

        $results = $this->generator->generateBatch($structureDefinitions, $this->tempOutputDir);

        self::assertCount(2, $results, 'Should return results for all StructureDefinitions');
        self::assertTrue($results[0], 'Patient generation should succeed');
        self::assertTrue($results[1], 'Observation generation should succeed');

        // Verify both files were generated
        self::assertFileExists($this->tempOutputDir . '/TestPatient.php');
        self::assertFileExists($this->tempOutputDir . '/TestObservation.php');
    }

    /**
     * Test generation with custom namespace
     */
    public function testGenerationWithCustomNamespace(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('Patient.json');
        $customNamespace     = 'Custom\\FHIR\\R4B';

        $result = $this->generator->generateFromStructureDefinition(
            $structureDefinition,
            $this->tempOutputDir,
            $customNamespace,
        );

        self::assertTrue($result, 'Generation with custom namespace should succeed');

        $generatedCode = file_get_contents($this->tempOutputDir . '/TestPatient.php');
        if ($generatedCode === false) {
            throw new \RuntimeException('Failed to read generated file for namespace test');
        }
        self::assertStringContainsString(
            "namespace {$customNamespace};",
            $generatedCode,
            'Generated code should use custom namespace',
        );

        $this->assertPsr4Namespace($customNamespace);
    }

    /**
     * Test incremental generation (avoiding regeneration of unchanged files)
     */
    public function testIncrementalGeneration(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('Patient.json');

        // First generation
        $this->generator->generateFromStructureDefinition(
            $structureDefinition,
            $this->tempOutputDir,
        );

        $firstGenerationTime = filemtime($this->tempOutputDir . '/TestPatient.php');

        // Wait a moment to ensure different timestamps
        sleep(1);

        // Second generation with same input
        $this->generator->generateFromStructureDefinition(
            $structureDefinition,
            $this->tempOutputDir,
        );

        $secondGenerationTime = filemtime($this->tempOutputDir . '/TestPatient.php');

        // File should not be regenerated if unchanged
        self::assertSame(
            $firstGenerationTime,
            $secondGenerationTime,
            'Unchanged files should not be regenerated',
        );
    }

    /**
     * Test error recovery during batch generation
     */
    public function testErrorRecoveryDuringBatchGeneration(): void
    {
        $structureDefinitions = [
            $this->loadTestStructureDefinition('Patient.json'),
            $this->loadTestStructureDefinition('InvalidStructureDefinition.json'),
            $this->loadTestStructureDefinition('Observation.json'),
        ];

        $results = $this->generator->generateBatch($structureDefinitions, $this->tempOutputDir);

        self::assertCount(3, $results, 'Should return results for all StructureDefinitions');
        self::assertTrue($results[0], 'Valid Patient should succeed');
        self::assertFalse($results[1], 'Invalid StructureDefinition should fail');
        self::assertTrue($results[2], 'Valid Observation should succeed despite previous failure');

        // Verify successful generations completed
        self::assertFileExists($this->tempOutputDir . '/TestPatient.php');
        self::assertFileExists($this->tempOutputDir . '/TestObservation.php');
        self::assertFileDoesNotExist($this->tempOutputDir . '/InvalidTest.php');
    }

    /**
     * Test memory usage during large batch generation
     */
    public function testMemoryUsageDuringBatchGeneration(): void
    {
        $initialMemory = memory_get_usage(true);

        // Generate multiple files to test memory management
        $structureDefinitions = array_fill(0, 10, $this->loadTestStructureDefinition('Patient.json'));

        $this->generator->generateBatch($structureDefinitions, $this->tempOutputDir);

        $finalMemory    = memory_get_usage(true);
        $memoryIncrease = $finalMemory - $initialMemory;

        // Memory increase should be reasonable (less than 50MB for this test)
        self::assertLessThan(
            50 * 1024 * 1024,
            $memoryIncrease,
            'Memory usage should remain reasonable during batch generation',
        );
    }

    /**
     * Test generated code follows PSR-12 standards
     */
    public function testGeneratedCodeFollowsPsr12(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('Patient.json');

        $this->generator->generateFromStructureDefinition(
            $structureDefinition,
            $this->tempOutputDir,
        );

        $generatedCode = file_get_contents($this->tempOutputDir . '/TestPatient.php');
        if ($generatedCode === false) {
            throw new \RuntimeException('Failed to read generated file for PSR-12 test');
        }

        // Check PSR-12 compliance
        self::assertStringContainsString('<?php', $generatedCode);
        self::assertStringContainsString('declare(strict_types=1);', $generatedCode);
        self::assertStringContainsString('namespace ', $generatedCode);
        self::assertStringContainsString('class TestPatient', $generatedCode);

        // Verify class name follows PSR-12
        $this->assertPsr12ClassName('TestPatient');
    }

    /**
     * Test cleanup of obsolete generated files
     */
    public function testCleanupOfObsoleteFiles(): void
    {
        // Create an obsolete file
        $obsoleteFile = $this->tempOutputDir . '/ObsoleteClass.php';
        file_put_contents($obsoleteFile, '<?php class ObsoleteClass {}');

        self::assertFileExists($obsoleteFile, 'Obsolete file should exist initially');

        // Generate new files
        $structureDefinition = $this->loadTestStructureDefinition('Patient.json');
        $this->generator->generateFromStructureDefinition(
            $structureDefinition,
            $this->tempOutputDir,
            null,
            true, // Enable cleanup
        );

        // Obsolete file should be removed if cleanup is enabled
        self::assertFileDoesNotExist($obsoleteFile, 'Obsolete files should be cleaned up');
        self::assertFileExists($this->tempOutputDir . '/TestPatient.php', 'New files should exist');
    }

    /**
     * Load test StructureDefinition from fixtures
     */
    /**
     * @return array<string, mixed>
     */
    private function loadTestStructureDefinition(string $filename): array
    {
        $path    = __DIR__ . '/../Fixtures/StructureDefinitions/' . $filename;
        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException("Failed to read test structure definition: {$path}");
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}
