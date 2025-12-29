<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Models;

use Ardenexal\FHIRTools\Component\Models\Exception\ModelsException;
use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Property-based test for ModelRegistry namespace resolution.
 *
 * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
 *
 * Tests that the ModelRegistry correctly resolves class names with proper
 * version-specific namespace isolation across all FHIR versions.
 */
class ModelRegistryPropertyTest extends TestCase
{
    use TestTrait;

    /**
     * Test that ModelRegistry provides version-specific namespace isolation.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
     * **Validates: Requirements 1.2, 1.5, 8.1-8.6**
     *
     * Property: For any FHIR version and model name, the ModelRegistry should generate
     * class names that are isolated to the specific version namespace without conflicts.
     */
    public function testVersionSpecificNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Patient', 'Observation', 'Practitioner', 'Organization']),
        )->then(function(string $version, string $resourceName): void {
            $className = ModelRegistry::getResourceClass($version, $resourceName);

            // Verify the class is in the correct version namespace
            self::assertStringContainsString("\\{$version}\\Resource\\", $className);

            // Verify the class name follows the expected pattern
            self::assertStringEndsWith("\\FHIR{$resourceName}", $className);

            // Verify no conflicts with other versions
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherClassName = ModelRegistry::getResourceClass($otherVersion, $resourceName);
                self::assertNotEquals($className, $otherClassName);
                self::assertStringContainsString("\\{$otherVersion}\\Resource\\", $otherClassName);
            }
        });
    }

    /**
     * Test that ModelRegistry correctly handles backbone element namespace isolation.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
     * **Validates: Requirements 1.2, 1.5, 8.1-8.6**
     *
     * Property: For any FHIR version, resource name, and backbone element name,
     * the ModelRegistry should generate properly isolated backbone element class names.
     */
    public function testBackboneElementNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Patient', 'Observation']),
            Generator\elements(['Contact', 'Communication', 'Component']),
        )->then(function(string $version, string $resourceName, string $elementName): void {
            $className = ModelRegistry::getBackboneElementClass($version, $resourceName, $elementName);

            // Verify the class is in the correct version and resource namespace
            self::assertStringContainsString("\\{$version}\\Resource\\{$resourceName}\\", $className);

            // Verify the class name follows the expected pattern
            self::assertStringEndsWith("\\FHIR{$resourceName}{$elementName}", $className);

            // Verify no conflicts with other versions
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherClassName = ModelRegistry::getBackboneElementClass($otherVersion, $resourceName, $elementName);
                self::assertNotEquals($className, $otherClassName);
                self::assertStringContainsString("\\{$otherVersion}\\Resource\\{$resourceName}\\", $otherClassName);
            }
        });
    }

    /**
     * Test that ModelRegistry correctly handles data type namespace isolation.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
     * **Validates: Requirements 1.2, 1.5, 8.1-8.6**
     *
     * Property: For any FHIR version and data type name, the ModelRegistry should
     * generate properly isolated data type class names.
     */
    public function testDataTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['HumanName', 'Address', 'ContactPoint', 'Identifier']),
        )->then(function(string $version, string $dataTypeName): void {
            $className = ModelRegistry::getDataTypeClass($version, $dataTypeName);

            // Verify the class is in the correct version namespace
            self::assertStringContainsString("\\{$version}\\DataType\\", $className);

            // Verify the class name follows the expected pattern
            self::assertStringEndsWith("\\FHIR{$dataTypeName}", $className);

            // Verify no conflicts with other versions
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherClassName = ModelRegistry::getDataTypeClass($otherVersion, $dataTypeName);
                self::assertNotEquals($className, $otherClassName);
                self::assertStringContainsString("\\{$otherVersion}\\DataType\\", $otherClassName);
            }
        });
    }

    /**
     * Test that ModelRegistry correctly handles primitive type namespace isolation.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
     * **Validates: Requirements 1.2, 1.5, 8.1-8.6**
     *
     * Property: For any FHIR version and primitive type name, the ModelRegistry should
     * generate properly isolated primitive type class names.
     */
    public function testPrimitiveTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['String', 'Integer', 'Boolean', 'Decimal', 'DateTime']),
        )->then(function(string $version, string $primitiveName): void {
            $className = ModelRegistry::getPrimitiveClass($version, $primitiveName);

            // Verify the class is in the correct version namespace
            self::assertStringContainsString("\\{$version}\\Primitive\\", $className);

            // Verify the class name follows the expected pattern
            self::assertStringEndsWith("\\FHIR{$primitiveName}", $className);

            // Verify no conflicts with other versions
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherClassName = ModelRegistry::getPrimitiveClass($otherVersion, $primitiveName);
                self::assertNotEquals($className, $otherClassName);
                self::assertStringContainsString("\\{$otherVersion}\\Primitive\\", $otherClassName);
            }
        });
    }

    /**
     * Test that ModelRegistry correctly handles enum namespace isolation.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
     * **Validates: Requirements 1.2, 1.5, 8.1-8.6**
     *
     * Property: For any FHIR version and enum name, the ModelRegistry should
     * generate properly isolated enum class names.
     */
    public function testEnumNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['AdministrativeGender', 'ObservationStatus', 'ContactPointSystem']),
        )->then(function(string $version, string $enumName): void {
            $className = ModelRegistry::getEnumClass($version, $enumName);

            // Verify the class is in the correct version namespace
            self::assertStringContainsString("\\{$version}\\Enum\\", $className);

            // Verify the class name follows the expected pattern
            self::assertStringEndsWith("\\FHIR{$enumName}", $className);

            // Verify no conflicts with other versions
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherClassName = ModelRegistry::getEnumClass($otherVersion, $enumName);
                self::assertNotEquals($className, $otherClassName);
                self::assertStringContainsString("\\{$otherVersion}\\Enum\\", $otherClassName);
            }
        });
    }

    /**
     * Test that ModelRegistry correctly handles code type namespace isolation.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
     * **Validates: Requirements 1.2, 1.5, 8.1-8.6**
     *
     * Property: For any FHIR version and enum name, the ModelRegistry should
     * generate properly isolated code type wrapper class names.
     */
    public function testCodeTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['AdministrativeGender', 'ObservationStatus', 'ContactPointSystem']),
        )->then(function(string $version, string $enumName): void {
            $className = ModelRegistry::getCodeTypeClass($version, $enumName);

            // Verify the class is in the correct version namespace (DataType for code types)
            self::assertStringContainsString("\\{$version}\\DataType\\", $className);

            // Verify the class name follows the expected pattern
            self::assertStringEndsWith("\\FHIR{$enumName}Type", $className);

            // Verify no conflicts with other versions
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                $otherClassName = ModelRegistry::getCodeTypeClass($otherVersion, $enumName);
                self::assertNotEquals($className, $otherClassName);
                self::assertStringContainsString("\\{$otherVersion}\\DataType\\", $otherClassName);
            }
        });
    }

    /**
     * Test that ModelRegistry throws appropriate exceptions for unsupported versions.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
     * **Validates: Requirements 1.2, 1.5, 8.1-8.6**
     *
     * Property: For any unsupported FHIR version, the ModelRegistry should throw
     * a ModelsException with appropriate error message.
     */
    public function testUnsupportedVersionHandling(): void
    {
        $this->forAll(
            Generator\elements(['R3', 'R6', 'DSTU2', 'STU3', 'invalid']),
            Generator\elements(['Patient', 'Observation']),
        )->then(function(string $unsupportedVersion, string $resourceName): void {
            $this->expectException(ModelsException::class);
            $this->expectExceptionMessage("Unsupported FHIR version: {$unsupportedVersion}");

            ModelRegistry::getResourceClass($unsupportedVersion, $resourceName);
        });
    }

    /**
     * Test that ModelRegistry version support methods work correctly.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation**
     * **Validates: Requirements 1.2, 1.5, 8.1-8.6**
     *
     * Property: For any version string, the ModelRegistry should correctly identify
     * whether it is supported and provide the complete list of supported versions.
     */
    public function testVersionSupportMethods(): void
    {
        $this->forAll(
            Generator\oneOf(
                Generator\elements(['R4', 'R4B', 'R5']),
                Generator\elements(['R3', 'R6', 'DSTU2', 'STU3', 'invalid']),
            ),
        )->then(function(string $version): void {
            $isSupported       = ModelRegistry::isSupportedVersion($version);
            $supportedVersions = ModelRegistry::getSupportedVersions();

            // Verify supported versions list is consistent
            self::assertEquals(['R4', 'R4B', 'R5'], $supportedVersions);

            // Verify version support detection is correct
            if (in_array($version, ['R4', 'R4B', 'R5'], true)) {
                self::assertTrue($isSupported, "Version {$version} should be supported");
                self::assertContains($version, $supportedVersions);
            } else {
                self::assertFalse($isSupported, "Version {$version} should not be supported");
                self::assertNotContains($version, $supportedVersions);
            }
        });
    }
}
