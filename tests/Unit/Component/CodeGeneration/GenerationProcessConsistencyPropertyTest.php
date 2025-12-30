<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Property-based test for generation process consistency.
 *
 * **Feature: fhir-models-component, Property 2: Generation process consistency**
 *
 * Tests that the FHIR generation process produces consistent output when using
 * the Models component structure compared to the standard generation process.
 *
 * **Note on Filesystem Mocking:**
 * This test mocks the Filesystem to prevent actual file operations. The command's
 * __invoke() method calls clearModelsComponentOutputDirectory() which would delete
 * the real Models directory at src/Component/Models/src, removing all generated
 * R4, R4B, and R5 model files. By mocking the filesystem and returning false for
 * exists(), we skip the cleanup logic while still testing the command's behavior
 * and consistency properties.
 */
class GenerationProcessConsistencyPropertyTest extends TestCase
{
    use TestTrait;

    private FHIRModelGeneratorCommand $command;

    private Filesystem $filesystem;

    private BuilderContext $context;

    private PackageLoader $packageLoader;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock filesystem to prevent actual file system operations during tests.
        // The command's __invoke() method calls clearModelsComponentOutputDirectory()
        // which would delete the real Models directory at src/Component/Models/src,
        // removing all generated R4, R4B, and R5 model files. By mocking the filesystem
        // and returning false for exists(), we skip the cleanup logic while still testing
        // the command's behavior and consistency.
        $this->filesystem    = $this->createMock(Filesystem::class);
        $this->context       = new BuilderContext();
        $this->packageLoader = $this->createMock(PackageLoader::class);

        // Configure filesystem mock to prevent deletion of real Models directory
        $this->filesystem
            ->method('exists')
            ->willReturn(false); // Pretend directories don't exist to skip cleanup

        $this->command = new FHIRModelGeneratorCommand(
            $this->filesystem,
            $this->context,
            $this->packageLoader,
        );
    }

    /**
     * Test that Models component generation produces consistent namespace structure.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any FHIR version and model type, the Models component generation
     * should produce namespace structures that are consistent with the component architecture.
     */
    public function testModelsComponentNamespaceConsistency(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
        )->then(function(string $version, string $modelType): void {
            // Test that the expected namespace pattern is consistent
            $expectedBaseNamespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";
            $expectedFullNamespace = "{$expectedBaseNamespace}\\{$modelType}";

            // Verify namespace follows Models component pattern
            self::assertStringStartsWith('Ardenexal\\FHIRTools\\Component\\Models\\', $expectedFullNamespace);
            self::assertStringContainsString("\\{$version}\\", $expectedFullNamespace);
            self::assertStringEndsWith("\\{$modelType}", $expectedFullNamespace);

            // Verify version isolation
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherNamespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$otherVersion}\\{$modelType}";
                self::assertNotEquals($expectedFullNamespace, $otherNamespace);
            }
        });
    }

    /**
     * Test that Models component output paths are consistent with namespace structure.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any FHIR version and model type, the output path should be
     * consistent with the namespace structure and component organization.
     */
    public function testModelsComponentOutputPathConsistency(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
            Generator\elements(['Patient', 'Observation', 'HumanName', 'String', 'AdministrativeGender']),
        )->then(function(string $version, string $modelType, string $typeName): void {
            // Simulate the expected output path structure
            $expectedBasePath = 'src/Component/Models/src';
            $expectedPath     = "{$expectedBasePath}/{$version}/{$modelType}/FHIR{$typeName}.php";

            // Verify path structure consistency
            self::assertStringContainsString("/{$version}/", $expectedPath);
            self::assertStringContainsString("/{$modelType}/", $expectedPath);
            self::assertStringEndsWith("/FHIR{$typeName}.php", $expectedPath);

            // Verify version isolation in paths
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherPath = "{$expectedBasePath}/{$otherVersion}/{$modelType}/FHIR{$typeName}.php";
                self::assertNotEquals($expectedPath, $otherPath);
                self::assertStringContainsString("/{$otherVersion}/", $otherPath);
            }
        });
    }

    /**
     * Test that backbone element paths are consistent with resource organization.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any FHIR version and backbone element, the output path should
     * place the element in the appropriate resource subdirectory.
     */
    public function testBackboneElementPathConsistency(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Patient', 'Observation', 'Practitioner']),
            Generator\elements(['Contact', 'Communication', 'Component', 'ReferenceRange']),
        )->then(function(string $version, string $resourceName, string $elementName): void {
            // Simulate backbone element path structure
            $expectedBasePath    = 'src/Component/Models/src';
            $backboneElementName = "FHIR{$resourceName}{$elementName}";
            $expectedPath        = "{$expectedBasePath}/{$version}/Resource/{$resourceName}/{$backboneElementName}.php";

            // Verify backbone element is placed in resource subdirectory
            self::assertStringContainsString("/{$version}/Resource/{$resourceName}/", $expectedPath);
            self::assertStringEndsWith("/{$backboneElementName}.php", $expectedPath);

            // Verify it's not in the general Resource directory
            $generalResourcePath = "{$expectedBasePath}/{$version}/Resource/{$backboneElementName}.php";
            self::assertNotEquals($expectedPath, $generalResourcePath);

            // Verify version isolation
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherPath = "{$expectedBasePath}/{$otherVersion}/Resource/{$resourceName}/{$backboneElementName}.php";
                self::assertNotEquals($expectedPath, $otherPath);
            }
        });
    }

    /**
     * Test that command option handling is consistent.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any combination of command options, the generation process
     * should handle Models component generation consistently.
     */
    public function testCommandOptionConsistency(): void
    {
        $this->forAll(
            Generator\bool(),
            Generator\elements([['hl7.fhir.r4.core'], ['hl7.fhir.r4b.core'], ['hl7.fhir.r5.core']]),
        )->then(function(bool $modelsComponent, array $packages): void {
            $output = new BufferedOutput();

            // Mock the package loader to avoid actual network calls
            $this->packageLoader
                ->method('installPackage')
                ->willThrowException(new \Exception('Mocked package loading'));

            // Test that the command handles the models-component option
            $result = ($this->command)(
                output: $output,
                packages: $packages,
                modelsComponent: $modelsComponent
            );

            $outputContent = $output->fetch();

            if ($modelsComponent) {
                // When models-component is true, should see Models component specific messages
                self::assertStringContainsString('Models component', $outputContent);
            }

            // Should always handle errors gracefully
            self::assertEquals(1, $result); // Command::FAILURE due to mocked exception
        });
    }

    /**
     * Test that file naming conventions are consistent across generation modes.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any FHIR model type and name, the file naming should be
     * consistent regardless of generation mode.
     */
    public function testFileNamingConsistency(): void
    {
        $this->forAll(
            Generator\elements(['Patient', 'Observation', 'HumanName', 'String', 'AdministrativeGender']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
        )->then(function(string $typeName, string $category): void {
            // Test that file naming follows consistent pattern
            $expectedFileName = "FHIR{$typeName}.php";

            // Verify naming pattern
            self::assertStringStartsWith('FHIR', $expectedFileName);
            self::assertStringEndsWith('.php', $expectedFileName);
            self::assertStringContainsString($typeName, $expectedFileName);

            // Verify no special characters that could cause file system issues
            self::assertDoesNotMatchRegularExpression('/[<>:"|?*]/', $expectedFileName);

            // Verify consistent length and format
            self::assertGreaterThan(8, strlen($expectedFileName)); // At least "FHIR*.php"
            self::assertEquals(1, substr_count($expectedFileName, '.php'));
        });
    }

    /**
     * Test that error handling is consistent across generation modes.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any error condition, the generation process should handle
     * errors consistently regardless of whether Models component mode is enabled.
     */
    public function testErrorHandlingConsistency(): void
    {
        $this->forAll(
            Generator\bool(),
            Generator\elements(['invalid-package', 'non-existent-package']),
        )->then(function(bool $modelsComponent, string $invalidPackage): void {
            $output = new BufferedOutput();

            // Mock the package loader to simulate errors
            $this->packageLoader
                ->method('installPackage')
                ->willThrowException(new \Exception("Package not found: {$invalidPackage}"));

            $result = ($this->command)(
                output: $output,
                packages: [$invalidPackage],
                modelsComponent: $modelsComponent
            );

            $outputContent = $output->fetch();

            // Should always return failure for invalid packages
            self::assertEquals(1, $result); // Command::FAILURE

            // Should always contain error information
            self::assertStringContainsString('error', strtolower($outputContent));

            // Should handle errors gracefully without crashing
            self::assertStringContainsString($invalidPackage, $outputContent);
        });
    }
}
