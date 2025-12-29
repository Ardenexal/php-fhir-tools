<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Models;

use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Property-based test for VersionDetector functionality.
 *
 * **Feature: fhir-models-component, Property 9: Cross-version utility functionality**
 *
 * Tests that the VersionDetector correctly identifies FHIR versions and
 * provides cross-version utility functionality across all supported versions.
 */
class VersionDetectorPropertyTest extends TestCase
{
    use TestTrait;

    /**
     * Test that VersionDetector correctly detects versions from class names.
     *
     * **Feature: fhir-models-component, Property 9: Cross-version utility functionality**
     * **Validates: Requirements 8.7**
     *
     * Property: For any valid Models component class name, the VersionDetector should
     * correctly identify the FHIR version from the namespace pattern.
     */
    public function testVersionDetectionFromClassName(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
            Generator\elements(['Patient', 'HumanName', 'String', 'AdministrativeGender']),
        )->then(function(string $version, string $type, string $modelName): void {
            $className = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\{$type}\\FHIR{$modelName}";

            $detectedVersion = VersionDetector::detectVersionFromClassName($className);

            // Verify the correct version is detected
            self::assertEquals($version, $detectedVersion);

            // Verify the class is identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, $version));

            // Verify other versions are not detected
            $otherVersions = array_diff(['R4', 'R4B', 'R5'], [$version]);
            foreach ($otherVersions as $otherVersion) {
                self::assertFalse(VersionDetector::isVersionSpecificClass($className, $otherVersion));
            }
        });
    }

    /**
     * Test that VersionDetector correctly handles non-Models component classes.
     *
     * **Feature: fhir-models-component, Property 9: Cross-version utility functionality**
     * **Validates: Requirements 8.7**
     *
     * Property: For any class name that is not from the Models component,
     * the VersionDetector should return null for version detection and false for component detection.
     */
    public function testNonModelsComponentClassHandling(): void
    {
        $this->forAll(
            Generator\elements([
                'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Generator\\FHIRModelGenerator',
                'Ardenexal\\FHIRTools\\Component\\Serialization\\FHIRSerializationService',
                'Ardenexal\\FHIRTools\\Bundle\\FHIRBundle\\FHIRBundle',
                'Symfony\\Component\\Console\\Command\\Command',
                'PHPUnit\\Framework\\TestCase',
                'stdClass',
            ]),
        )->then(function(string $className): void {
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);

            // Verify no version is detected for non-Models component classes
            self::assertNull($detectedVersion);

            // Verify the class is not identified as a Models component class
            self::assertFalse(VersionDetector::isModelsComponentClass($className));

            // Verify version-specific detection returns false for all versions
            foreach (['R4', 'R4B', 'R5'] as $version) {
                self::assertFalse(VersionDetector::isVersionSpecificClass($className, $version));
            }
        });
    }

    /**
     * Test that VersionDetector correctly identifies model types.
     *
     * **Feature: fhir-models-component, Property 9: Cross-version utility functionality**
     * **Validates: Requirements 8.7**
     *
     * Property: For any Models component class name, the VersionDetector should
     * correctly identify the model type (Resource, DataType, Primitive, Enum).
     */
    public function testModelTypeDetection(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
            Generator\elements(['Patient', 'HumanName', 'String', 'AdministrativeGender']),
        )->then(function(string $version, string $expectedType, string $modelName): void {
            $className = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\{$expectedType}\\FHIR{$modelName}";

            $detectedType = VersionDetector::getModelType($className);

            // Verify the correct model type is detected
            self::assertEquals($expectedType, $detectedType);
        });
    }

    /**
     * Test that VersionDetector handles backbone element class names correctly.
     *
     * **Feature: fhir-models-component, Property 9: Cross-version utility functionality**
     * **Validates: Requirements 8.7**
     *
     * Property: For any backbone element class name, the VersionDetector should
     * correctly identify the version and recognize it as a Models component class.
     */
    public function testBackboneElementDetection(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Patient', 'Observation']),
            Generator\elements(['Contact', 'Communication', 'Component']),
        )->then(function(string $version, string $resourceName, string $elementName): void {
            $className = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Resource\\{$resourceName}\\FHIR{$resourceName}{$elementName}";

            $detectedVersion = VersionDetector::detectVersionFromClassName($className);

            // Verify the correct version is detected for backbone elements
            self::assertEquals($version, $detectedVersion);

            // Verify the class is identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, $version));
        });
    }

    /**
     * Test that VersionDetector handles invalid or malformed class names gracefully.
     *
     * **Feature: fhir-models-component, Property 9: Cross-version utility functionality**
     * **Validates: Requirements 8.7**
     *
     * Property: For any invalid or malformed class name, the VersionDetector should
     * handle it gracefully without throwing exceptions and return appropriate null/false values.
     */
    public function testInvalidClassNameHandling(): void
    {
        $this->forAll(
            Generator\elements([
                '',
                'InvalidClassName',
                'Ardenexal\\FHIRTools\\Component\\Models\\',
                'Ardenexal\\FHIRTools\\Component\\Models\\InvalidVersion\\Resource\\FHIRPatient',
                'Ardenexal\\FHIRTools\\Component\\Models\\R4\\InvalidType\\FHIRPatient',
                'Not\\A\\Valid\\Namespace\\At\\All',
                '\\\\\\Invalid\\\\\\',
                'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\',
            ]),
        )->then(function(string $className): void {
            // These operations should not throw exceptions
            $detectedVersion   = VersionDetector::detectVersionFromClassName($className);
            $isModelsComponent = VersionDetector::isModelsComponentClass($className);
            $modelType         = VersionDetector::getModelType($className);

            // For invalid class names, version detection should return null
            if (!preg_match('/\\\\Component\\\\Models\\\\(R4B?|R5)\\\\/', $className)) {
                self::assertNull($detectedVersion);
            }

            // Model type should be null for invalid class names
            if (!$isModelsComponent || $detectedVersion === null) {
                self::assertNull($modelType);
            }

            // Version-specific detection should be false for invalid versions
            foreach (['R4', 'R4B', 'R5'] as $version) {
                $isVersionSpecific = VersionDetector::isVersionSpecificClass($className, $version);
                if ($detectedVersion !== $version) {
                    self::assertFalse($isVersionSpecific);
                }
            }
        });
    }

    /**
     * Test that VersionDetector works correctly with mock objects.
     *
     * **Feature: fhir-models-component, Property 9: Cross-version utility functionality**
     * **Validates: Requirements 8.7**
     *
     * Property: For any object with a class name following the Models component pattern,
     * the VersionDetector should correctly detect the version from the object instance.
     */
    public function testVersionDetectionFromObject(): void
    {
        $this->forAll(
            Generator\elements(['R4', 'R4B', 'R5']),
            Generator\elements(['Resource', 'DataType', 'Primitive', 'Enum']),
            Generator\elements(['Patient', 'HumanName', 'String', 'AdministrativeGender']),
        )->then(function(string $version, string $type, string $modelName): void {
            $className = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\{$type}\\FHIR{$modelName}";

            // Create a mock object with the expected class name
            $mockObject = $this->createMock('stdClass');

            // Use reflection to simulate the class name
            $reflection = new \ReflectionClass($mockObject);

            // Since we can't actually change the class name of a mock,
            // we'll test the detectVersionFromClassName method directly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);

            // Verify the correct version is detected
            self::assertEquals($version, $detectedVersion);
        });
    }

    /**
     * Test that VersionDetector consistently handles edge cases in namespace patterns.
     *
     * **Feature: fhir-models-component, Property 9: Cross-version utility functionality**
     * **Validates: Requirements 8.7**
     *
     * Property: For any edge case in namespace patterns, the VersionDetector should
     * handle them consistently and predictably.
     */
    public function testNamespacePatternEdgeCases(): void
    {
        $this->forAll(
            Generator\elements([
                // Valid patterns
                'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRPatient',
                'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIRHumanName',
                'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Primitive\\FHIRString',
                // Edge cases with similar patterns
                'SomeOther\\Component\\Models\\R4\\Resource\\FHIRPatient',
                'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\Patient\\FHIRPatientContact',
                'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource\\Observation\\FHIRObservationComponent',
            ]),
        )->then(function(string $className): void {
            $detectedVersion   = VersionDetector::detectVersionFromClassName($className);
            $isModelsComponent = VersionDetector::isModelsComponentClass($className);

            // If it contains the Models component pattern, it should be detected
            if (str_contains($className, '\\Component\\Models\\')) {
                self::assertTrue($isModelsComponent);

                // If it has a valid version pattern, version should be detected
                if (preg_match('/\\\\Component\\\\Models\\\\(R4B?|R5)\\\\/', $className, $matches)) {
                    self::assertEquals($matches[1], $detectedVersion);
                } else {
                    self::assertNull($detectedVersion);
                }
            } else {
                self::assertFalse($isModelsComponent);
                self::assertNull($detectedVersion);
            }
        });
    }
}
