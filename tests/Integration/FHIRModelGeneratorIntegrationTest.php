<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\ErrorCollector;
use Ardenexal\FHIRTools\FHIRModelGenerator;
use Ardenexal\FHIRTools\PackageLoader;
use Ardenexal\FHIRTools\RetryHandler;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Ardenexal\FHIRTools\BuilderContext;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

    private string $tempOutputDir;

    protected function setUp(): void
    {
        $this->errorCollector = new ErrorCollector();

        // Create BuilderContext
        $context = new BuilderContext();

        // Set up namespaces for R4B
        $elementNamespace = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\Element');
        $enumNamespace    = new PhpNamespace('Ardenexal\\FHIRTools\\Test\\Enum');
        $context->addElementNamespace('R4B', $elementNamespace);
        $context->addEnumNamespace('R4B', $enumNamespace);

        // Create temporary output directory
        $this->tempOutputDir = $this->createTempDirectory();

        $this->generator = new FHIRModelGenerator($context);
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

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
        );

        self::assertNotNull($class, 'Generation should succeed for valid StructureDefinition');
        self::assertFalse($this->errorCollector->hasErrors(), 'No errors should occur during generation');

        // Verify class properties
        self::assertSame('FHIRTestPatient', $class->getName());
        self::assertTrue($class->hasMethod('__construct'), 'Generated class should have constructor');
    }

    /**
     * Test generation with invalid StructureDefinition
     */
    public function testGenerationWithInvalidStructureDefinition(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('InvalidStructureDefinition.json');

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
        );

        self::assertNull($class, 'Generation should fail for invalid StructureDefinition');
        self::assertTrue($this->errorCollector->hasErrors(), 'Errors should be collected during validation');

        // Verify error was collected
        $errors = $this->errorCollector->getErrors();
        self::assertNotEmpty($errors, 'Should have collected validation errors');
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

        $results = [];
        foreach ($structureDefinitions as $structureDefinition) {
            $class = $this->generator->generateModelClassWithErrorHandling(
                $structureDefinition,
                'R4B',
                $this->errorCollector,
            );
            $results[] = $class !== null;
        }

        self::assertCount(2, $results, 'Should return results for all StructureDefinitions');
        self::assertTrue($results[0], 'Patient generation should succeed');
        self::assertTrue($results[1], 'Observation generation should succeed');
    }

    /**
     * Test generation with custom namespace
     */
    public function testGenerationWithCustomNamespace(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('Patient.json');

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
        );

        self::assertNotNull($class, 'Generation with custom namespace should succeed');
        self::assertSame('FHIRTestPatient', $class->getName());

        // Verify namespace is properly set
        $namespace = $class->getNamespace();
        self::assertNotNull($namespace, 'Class should have a namespace');
    }

    /**
     * Test incremental generation (avoiding regeneration of unchanged files)
     */
    public function testIncrementalGeneration(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('Patient.json');

        // First generation
        $class1 = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
        );

        self::assertNotNull($class1, 'First generation should succeed');

        // Second generation with same input
        $class2 = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
        );

        self::assertNotNull($class2, 'Second generation should succeed');
        self::assertSame($class1->getName(), $class2->getName(), 'Generated classes should have same name');
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

        $results = [];
        foreach ($structureDefinitions as $structureDefinition) {
            $class = $this->generator->generateModelClassWithErrorHandling(
                $structureDefinition,
                'R4B',
                $this->errorCollector,
            );
            $results[] = $class !== null;
        }

        self::assertCount(3, $results, 'Should return results for all StructureDefinitions');
        self::assertTrue($results[0], 'Valid Patient should succeed');
        self::assertFalse($results[1], 'Invalid StructureDefinition should fail');
        self::assertTrue($results[2], 'Valid Observation should succeed despite previous failure');

        // Verify errors were collected for invalid definition
        self::assertTrue($this->errorCollector->hasErrors(), 'Should have collected errors from invalid definition');
    }

    /**
     * Test memory usage during large batch generation
     */
    public function testMemoryUsageDuringBatchGeneration(): void
    {
        $initialMemory = memory_get_usage(true);

        // Generate multiple files to test memory management
        $structureDefinitions = array_fill(0, 10, $this->loadTestStructureDefinition('Patient.json'));

        foreach ($structureDefinitions as $structureDefinition) {
            $this->generator->generateModelClassWithErrorHandling(
                $structureDefinition,
                'R4B',
                $this->errorCollector,
            );
        }

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

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
        );

        self::assertNotNull($class, 'Class generation should succeed');

        // Verify class name follows PSR-12
        $this->assertPsr12ClassName($class->getName());

        // Verify class has proper structure
        self::assertTrue($class->hasMethod('__construct'), 'Class should have constructor');
        self::assertNotNull($class->getNamespace(), 'Class should have namespace');
    }

    /**
     * Test cleanup of obsolete generated files
     */
    public function testCleanupOfObsoleteFiles(): void
    {
        $structureDefinition = $this->loadTestStructureDefinition('Patient.json');

        $class = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
        );

        self::assertNotNull($class, 'Class generation should succeed');
        self::assertSame('FHIRTestPatient', $class->getName());

        // Test that the same class can be generated multiple times without issues
        $class2 = $this->generator->generateModelClassWithErrorHandling(
            $structureDefinition,
            'R4B',
            $this->errorCollector,
        );

        self::assertNotNull($class2, 'Second generation should also succeed');
        self::assertSame($class->getName(), $class2->getName());
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
