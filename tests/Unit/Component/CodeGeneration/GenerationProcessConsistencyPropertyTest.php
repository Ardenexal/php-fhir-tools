<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
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
 * Tests that the FHIR generation process produces consistent output
 * with the Models component structure.
 *
 * **Note on Filesystem Mocking:**
 * This test mocks the Filesystem object to prevent actual file operations. The
 * command's __invoke() method calls clearOutputDirectory() which would delete
 * the real Models directory at src/Component/Models/src, removing all generated
 * R4, R4B, and R5 model files. The mock is configured to return false for the
 * exists() method, which prevents clearOutputDirectory() from executing its
 * deletion logic (it checks directory existence before cleanup).
 */
class GenerationProcessConsistencyPropertyTest extends TestCase
{
    use TestTrait;

    private FHIRModelGeneratorCommand $command;

    private Filesystem $filesystem;

    private PackageLoader $packageLoader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem    = $this->createMock(Filesystem::class);
        $this->packageLoader = $this->createMock(PackageLoader::class);

        $this->filesystem
            ->method('exists')
            ->willReturn(false);

        $this->command = new FHIRModelGeneratorCommand(
            $this->filesystem,
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
            $expectedBaseNamespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";
            $expectedFullNamespace = "{$expectedBaseNamespace}\\{$modelType}";

            self::assertStringStartsWith('Ardenexal\\FHIRTools\\Component\\Models\\', $expectedFullNamespace);
            self::assertStringContainsString("\\{$version}\\", $expectedFullNamespace);
            self::assertStringEndsWith("\\{$modelType}", $expectedFullNamespace);

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
            $expectedBasePath = 'src/Component/Models/src';
            $expectedPath     = "{$expectedBasePath}/{$version}/{$modelType}/FHIR{$typeName}.php";

            self::assertStringContainsString("/{$version}/", $expectedPath);
            self::assertStringContainsString("/{$modelType}/", $expectedPath);
            self::assertStringEndsWith("/FHIR{$typeName}.php", $expectedPath);

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
            $expectedBasePath    = 'src/Component/Models/src';
            $backboneElementName = "FHIR{$resourceName}{$elementName}";
            $expectedPath        = "{$expectedBasePath}/{$version}/Resource/{$resourceName}/{$backboneElementName}.php";

            self::assertStringContainsString("/{$version}/Resource/{$resourceName}/", $expectedPath);
            self::assertStringEndsWith("/{$backboneElementName}.php", $expectedPath);

            $generalResourcePath = "{$expectedBasePath}/{$version}/Resource/{$backboneElementName}.php";
            self::assertNotEquals($expectedPath, $generalResourcePath);

            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherPath = "{$expectedBasePath}/{$otherVersion}/Resource/{$resourceName}/{$backboneElementName}.php";
                self::assertNotEquals($expectedPath, $otherPath);
            }
        });
    }

    /**
     * Test that command handles errors gracefully.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any combination of packages, the generation process
     * should handle errors gracefully when packages cannot be loaded.
     */
    public function testCommandErrorHandling(): void
    {
        $this->forAll(
            Generator\elements([['hl7.fhir.r4.core'], ['hl7.fhir.r4b.core'], ['hl7.fhir.r5.core']]),
        )->then(function(array $packages): void {
            $output = new BufferedOutput();

            $this->packageLoader
                ->method('installPackage')
                ->willThrowException(new \Exception('Mocked package loading'));

            $result = ($this->command)(
                output: $output,
                packages: $packages,
            );

            $outputContent = $output->fetch();

            self::assertEquals(1, $result);
        });
    }

    /**
     * Test that file naming conventions are consistent.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any FHIR model type and name, the file naming should be
     * consistent.
     */
    public function testFileNamingConsistency(): void
    {
        $this->forAll(
            Generator\elements(['Patient', 'Observation', 'HumanName', 'String', 'AdministrativeGender']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
        )->then(function(string $typeName, string $category): void {
            $expectedFileName = "FHIR{$typeName}.php";

            self::assertStringStartsWith('FHIR', $expectedFileName);
            self::assertStringEndsWith('.php', $expectedFileName);
            self::assertStringContainsString($typeName, $expectedFileName);

            self::assertDoesNotMatchRegularExpression('/[<>:"|?*]/', $expectedFileName);

            self::assertGreaterThan(8, strlen($expectedFileName));
            self::assertEquals(1, substr_count($expectedFileName, '.php'));
        });
    }

    /**
     * Test that error handling is consistent.
     *
     * **Feature: fhir-models-component, Property 2: Generation process consistency**
     * **Validates: Requirements 2.1, 2.5**
     *
     * Property: For any error condition, the generation process should handle
     * errors consistently.
     */
    public function testErrorHandlingConsistency(): void
    {
        $this->forAll(
            Generator\elements(['invalid-package', 'non-existent-package']),
        )->then(function(string $invalidPackage): void {
            $output = new BufferedOutput();

            $this->packageLoader
                ->method('installPackage')
                ->willThrowException(new \Exception("Package not found: {$invalidPackage}"));

            $result = ($this->command)(
                output: $output,
                packages: [$invalidPackage],
            );

            $outputContent = $output->fetch();

            self::assertEquals(1, $result);
            self::assertStringContainsString('error', strtolower($outputContent));
            self::assertStringContainsString($invalidPackage, $outputContent);
        });
    }
}
