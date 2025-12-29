<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Unit tests for output path generation in FHIRModelGeneratorCommand.
 *
 * Tests the Models component output path generation logic to ensure proper
 * organization of generated files according to the component structure.
 */
class OutputPathGenerationTest extends TestCase
{
    private FHIRModelGeneratorCommand $command;

    private \ReflectionClass $reflection;

    private \ReflectionMethod $getModelsComponentOutputPathMethod;

    private \ReflectionMethod $isBackboneElementMethod;

    private \ReflectionMethod $extractResourceNameMethod;

    protected function setUp(): void
    {
        parent::setUp();

        $filesystem    = new Filesystem();
        $context       = new BuilderContext();
        $packageLoader = $this->createMock(PackageLoader::class);

        $this->command    = new FHIRModelGeneratorCommand($filesystem, $context, $packageLoader);
        $this->reflection = new \ReflectionClass($this->command);

        // Make private methods accessible for testing
        $this->getModelsComponentOutputPathMethod = $this->reflection->getMethod('getModelsComponentOutputPath');
        $this->getModelsComponentOutputPathMethod->setAccessible(true);

        $this->isBackboneElementMethod = $this->reflection->getMethod('isBackboneElement');
        $this->isBackboneElementMethod->setAccessible(true);

        $this->extractResourceNameMethod = $this->reflection->getMethod('extractResourceNameFromBackboneElement');
        $this->extractResourceNameMethod->setAccessible(true);
    }

    /**
     * Test resource output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testResourceOutputPaths(): void
    {
        $version   = 'R4B';
        $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource");

        // Test regular resource class
        $resourceClass = new ClassType('FHIRPatient');
        $outputPath    = $this->getModelsComponentOutputPathMethod->invoke(
            $this->command,
            $version,
            $resourceClass,
            $namespace,
        );

        $expectedPath = 'src/Component/CodeGeneration/src/Component/Models/src/R4B/Resource/FHIRPatient.php';
        self::assertStringEndsWith('R4B/Resource/FHIRPatient.php', $outputPath);
        self::assertStringContainsString('/Models/src/', $outputPath);
    }

    /**
     * Test backbone element output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testBackboneElementOutputPaths(): void
    {
        $version   = 'R4B';
        $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource");

        // Create a backbone element class with proper attribute
        $backboneClass = new ClassType('FHIRPatientContact');
        $backboneClass->addAttribute('Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Attributes\\FHIRBackboneElement', [
            'parentResource' => 'Patient',
            'elementPath'    => 'Patient.contact',
            'fhirVersion'    => $version,
        ]);

        $outputPath = $this->getModelsComponentOutputPathMethod->invoke(
            $this->command,
            $version,
            $backboneClass,
            $namespace,
        );

        // Backbone elements should be in resource subdirectories
        self::assertStringEndsWith('R4B/Resource/Patient/FHIRPatientContact.php', $outputPath);
        self::assertStringContainsString('/Models/src/', $outputPath);
    }

    /**
     * Test data type output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testDataTypeOutputPaths(): void
    {
        $version   = 'R4B';
        $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\DataType");

        $dataTypeClass = new ClassType('FHIRHumanName');
        $outputPath    = $this->getModelsComponentOutputPathMethod->invoke(
            $this->command,
            $version,
            $dataTypeClass,
            $namespace,
        );

        self::assertStringEndsWith('R4B/DataType/FHIRHumanName.php', $outputPath);
        self::assertStringContainsString('/Models/src/', $outputPath);
    }

    /**
     * Test primitive type output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testPrimitiveOutputPaths(): void
    {
        $version   = 'R4B';
        $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Primitive");

        $primitiveClass = new ClassType('FHIRString');
        $outputPath     = $this->getModelsComponentOutputPathMethod->invoke(
            $this->command,
            $version,
            $primitiveClass,
            $namespace,
        );

        self::assertStringEndsWith('R4B/Primitive/FHIRString.php', $outputPath);
        self::assertStringContainsString('/Models/src/', $outputPath);
    }

    /**
     * Test enum output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testEnumOutputPaths(): void
    {
        $version   = 'R4B';
        $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Enum");

        $enumClass  = new EnumType('FHIRAdministrativeGender');
        $outputPath = $this->getModelsComponentOutputPathMethod->invoke(
            $this->command,
            $version,
            $enumClass,
            $namespace,
        );

        self::assertStringEndsWith('R4B/Enum/FHIRAdministrativeGender.php', $outputPath);
        self::assertStringContainsString('/Models/src/', $outputPath);
    }

    /**
     * Test code type output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testCodeTypeOutputPaths(): void
    {
        $version   = 'R4B';
        $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\DataType");

        $codeTypeClass = new ClassType('FHIRAdministrativeGenderType');
        $outputPath    = $this->getModelsComponentOutputPathMethod->invoke(
            $this->command,
            $version,
            $codeTypeClass,
            $namespace,
        );

        self::assertStringEndsWith('R4B/DataType/FHIRAdministrativeGenderType.php', $outputPath);
        self::assertStringContainsString('/Models/src/', $outputPath);
    }

    /**
     * Test backbone element detection works correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testBackboneElementDetection(): void
    {
        // Test with proper backbone element attribute
        $backboneClass = new ClassType('FHIRPatientContact');
        $backboneClass->addAttribute('Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Attributes\\FHIRBackboneElement', [
            'parentResource' => 'Patient',
            'elementPath'    => 'Patient.contact',
        ]);

        $isBackbone = $this->isBackboneElementMethod->invoke(
            $this->command,
            'FHIRPatientContact',
            $backboneClass,
        );

        self::assertTrue($isBackbone);

        // Test with regular class (no backbone attribute)
        $regularClass = new ClassType('FHIRPatient');
        $isBackbone   = $this->isBackboneElementMethod->invoke(
            $this->command,
            'FHIRPatient',
            $regularClass,
        );

        self::assertFalse($isBackbone);

        // Test fallback naming pattern detection
        $patternClass = new ClassType('FHIRObservationComponent');
        $isBackbone   = $this->isBackboneElementMethod->invoke(
            $this->command,
            'FHIRObservationComponent',
            $patternClass,
        );

        self::assertTrue($isBackbone); // Should detect by naming pattern
    }

    /**
     * Test resource name extraction from backbone elements.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testResourceNameExtraction(): void
    {
        // Test with proper backbone element attribute
        $backboneClass = new ClassType('FHIRPatientContact');
        $backboneClass->addAttribute('Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Attributes\\FHIRBackboneElement', [
            'parentResource' => 'Patient',
            'elementPath'    => 'Patient.contact',
        ]);

        $resourceName = $this->extractResourceNameMethod->invoke(
            $this->command,
            'FHIRPatientContact',
            $backboneClass,
        );

        self::assertEquals('Patient', $resourceName);

        // Test fallback naming pattern extraction
        $patternClass = new ClassType('FHIRObservationComponent');
        $resourceName = $this->extractResourceNameMethod->invoke(
            $this->command,
            'FHIRObservationComponent',
            $patternClass,
        );

        self::assertEquals('Observation', $resourceName);

        // Test with complex backbone element name
        $complexClass = new ClassType('FHIRPractitionerQualification');
        $resourceName = $this->extractResourceNameMethod->invoke(
            $this->command,
            'FHIRPractitionerQualification',
            $complexClass,
        );

        self::assertEquals('Practitioner', $resourceName);
    }

    /**
     * Test version-specific path generation.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testVersionSpecificPaths(): void
    {
        $versions = ['R4', 'R4B', 'R5'];

        foreach ($versions as $version) {
            $namespace     = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource");
            $resourceClass = new ClassType('FHIRPatient');

            $outputPath = $this->getModelsComponentOutputPathMethod->invoke(
                $this->command,
                $version,
                $resourceClass,
                $namespace,
            );

            self::assertStringContainsString("/{$version}/", $outputPath);
            self::assertStringEndsWith("{$version}/Resource/FHIRPatient.php", $outputPath);
        }
    }

    /**
     * Test path generation handles edge cases correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testEdgeCases(): void
    {
        $version = 'R4B';

        // Test with minimal valid class name
        $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource");
        $class     = new ClassType('A');

        $outputPath = $this->getModelsComponentOutputPathMethod->invoke(
            $this->command,
            $version,
            $class,
            $namespace,
        );

        // Should still generate a valid path structure
        self::assertStringContainsString('/Models/src/', $outputPath);
        self::assertStringContainsString('/R4B/Resource/', $outputPath);
        self::assertStringEndsWith('/A.php', $outputPath);

        // Test with very long backbone element name
        $longBackboneClass = new ClassType('FHIRPatientContactRelationshipVeryLongElementName');
        $resourceName      = $this->extractResourceNameMethod->invoke(
            $this->command,
            'FHIRPatientContactRelationshipVeryLongElementName',
            $longBackboneClass,
        );

        self::assertEquals('Patient', $resourceName);
    }

    /**
     * Test that different model types get different paths.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testModelTypePathDifferentiation(): void
    {
        $version  = 'R4B';
        $typeName = 'FHIRString';

        // Test different namespace categories
        $categories = ['Resource', 'DataType', 'Primitive', 'Enum'];
        $paths      = [];

        foreach ($categories as $category) {
            $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\{$category}");
            $class     = $category === 'Enum' ? new EnumType($typeName) : new ClassType($typeName);

            $outputPath = $this->getModelsComponentOutputPathMethod->invoke(
                $this->command,
                $version,
                $class,
                $namespace,
            );

            $paths[$category] = $outputPath;
            self::assertStringContainsString("/{$category}/", $outputPath);
        }

        // Ensure all paths are different
        self::assertCount(4, array_unique($paths));
    }
}
