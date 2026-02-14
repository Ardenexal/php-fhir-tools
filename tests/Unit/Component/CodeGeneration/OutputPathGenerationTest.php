<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
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

    private \ReflectionMethod $getOutputPathMethod;

    private \ReflectionMethod $getBackboneParentResourceMethod;

    protected function setUp(): void
    {
        parent::setUp();

        $filesystem    = new Filesystem();
        $packageLoader = $this->createMock(PackageLoader::class);

        $this->command    = new FHIRModelGeneratorCommand($filesystem, $packageLoader);
        $reflection = new \ReflectionClass($this->command);

        // Make private methods accessible for testing
        $this->getOutputPathMethod = $reflection->getMethod('getOutputPath');
        $this->getOutputPathMethod->setAccessible(true);

        $this->getBackboneParentResourceMethod = $reflection->getMethod('getBackboneParentResource');
        $this->getBackboneParentResourceMethod->setAccessible(true);
    }

    /**
     * Helper method to verify output path for a given type
     *
     * @param string         $version       FHIR version (R4, R4B, R5)
     * @param string         $category      Namespace category (Resource, DataType, Primitive, Enum)
     * @param string         $className     The class name
     * @param string         $expectedPath  Expected path suffix
     * @param ClassType|null $classType     Optional pre-configured ClassType
     */
    private function verifyOutputPath(
        string $version,
        string $category,
        string $className,
        string $expectedPath,
        ?ClassType $classType = null
    ): void {
        $namespace = new PhpNamespace("Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\{$category}");
        $class     = $classType ?? new ClassType($className);

        $outputPath = $this->getOutputPathMethod->invoke(
            $this->command,
            $version,
            $class,
            $namespace,
        );

        self::assertStringEndsWith($expectedPath, $outputPath);
        self::assertStringContainsString('/Models/src/', $outputPath);
    }

    /**
     * Helper method to create a backbone element class with proper attributes
     *
     * @param string $className      The class name
     * @param string $parentResource The parent resource name
     * @param string $elementPath    The element path
     * @param string $version        FHIR version
     *
     * @return ClassType
     */
    private function createBackboneElement(
        string $className,
        string $parentResource,
        string $elementPath,
        string $version = 'R4B'
    ): ClassType {
        $class = new ClassType($className);
        $class->addAttribute('Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Attributes\\FHIRBackboneElement', [
            'parentResource' => $parentResource,
            'elementPath'    => $elementPath,
            'fhirVersion'    => $version,
        ]);

        return $class;
    }

    /**
     * Test resource output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testResourceOutputPaths(): void
    {
        $this->verifyOutputPath('R4B', 'Resource', 'FHIRPatient', 'R4B/Resource/FHIRPatient.php');
    }

    /**
     * Test backbone element output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testBackboneElementOutputPaths(): void
    {
        $backboneClass = $this->createBackboneElement('FHIRPatientContact', 'Patient', 'Patient.contact');
        $this->verifyOutputPath('R4B', 'Resource', 'FHIRPatientContact', 'R4B/Resource/Patient/FHIRPatientContact.php', $backboneClass);
    }

    /**
     * Test data type output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testDataTypeOutputPaths(): void
    {
        $this->verifyOutputPath('R4B', 'DataType', 'FHIRHumanName', 'R4B/DataType/FHIRHumanName.php');
    }

    /**
     * Test primitive type output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testPrimitiveOutputPaths(): void
    {
        $this->verifyOutputPath('R4B', 'Primitive', 'FHIRString', 'R4B/Primitive/FHIRString.php');
    }

    /**
     * Test enum output paths are generated correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testEnumOutputPaths(): void
    {
        $namespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Enum');
        $enumClass = new EnumType('FHIRAdministrativeGender');

        $outputPath = $this->getOutputPathMethod->invoke(
            $this->command,
            'R4B',
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
        $this->verifyOutputPath('R4B', 'DataType', 'FHIRAdministrativeGenderType', 'R4B/DataType/FHIRAdministrativeGenderType.php');
    }

    /**
     * Test backbone element detection works correctly.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testBackboneElementDetection(): void
    {
        // Test with proper backbone element attribute
        $backboneClass = $this->createBackboneElement('FHIRPatientContact', 'Patient', 'Patient.contact');

        $parentResource = $this->getBackboneParentResourceMethod->invoke(
            $this->command,
            $backboneClass,
        );

        self::assertNotNull($parentResource);
        self::assertEquals('Patient', $parentResource);

        // Test with regular class (no backbone attribute)
        $regularClass   = new ClassType('FHIRPatient');
        $parentResource = $this->getBackboneParentResourceMethod->invoke(
            $this->command,
            $regularClass,
        );

        self::assertNull($parentResource);
    }

    /**
     * Test resource name extraction from backbone elements.
     *
     * **Validates: Requirements 3.1, 3.3**
     */
    public function testResourceNameExtraction(): void
    {
        // Test with proper backbone element attribute
        $backboneClass = $this->createBackboneElement('FHIRPatientContact', 'Patient', 'Patient.contact');

        $resourceName = $this->getBackboneParentResourceMethod->invoke(
            $this->command,
            $backboneClass,
        );

        self::assertEquals('Patient', $resourceName);

        // Test with complex backbone element name
        $complexClass = $this->createBackboneElement('FHIRPractitionerQualification', 'Practitioner', 'Practitioner.qualification');

        $resourceName = $this->getBackboneParentResourceMethod->invoke(
            $this->command,
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

            $outputPath = $this->getOutputPathMethod->invoke(
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
        // Test with minimal valid class name
        $this->verifyOutputPath('R4B', 'Resource', 'A', 'R4B/Resource/A.php');

        // Test with very long backbone element name
        $longBackboneClass = $this->createBackboneElement(
            'FHIRPatientContactRelationshipVeryLongElementName',
            'Patient',
            'Patient.contact.relationship'
        );

        $resourceName = $this->getBackboneParentResourceMethod->invoke(
            $this->command,
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

            $outputPath = $this->getOutputPathMethod->invoke(
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
