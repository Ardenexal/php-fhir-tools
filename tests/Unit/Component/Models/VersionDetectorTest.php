<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Models;

use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for VersionDetector utility class.
 *
 * Tests specific examples and edge cases for the VersionDetector functionality.
 */
class VersionDetectorTest extends TestCase
{
    /**
     * Test that detectVersionFromClassName correctly identifies versions.
     */
    public function testDetectVersionFromClassNameIdentifiesVersions(): void
    {
        // Test R4 version detection
        $r4Class = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRPatient';
        self::assertEquals('R4', VersionDetector::detectVersionFromClassName($r4Class));

        // Test R4B version detection
        $r4bClass = 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIRHumanName';
        self::assertEquals('R4B', VersionDetector::detectVersionFromClassName($r4bClass));

        // Test R5 version detection
        $r5Class = 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Primitive\\FHIRString';
        self::assertEquals('R5', VersionDetector::detectVersionFromClassName($r5Class));
    }

    /**
     * Test that detectVersionFromClassName returns null for non-Models classes.
     */
    public function testDetectVersionFromClassNameReturnsNullForNonModelsClasses(): void
    {
        $nonModelsClasses = [
            'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Generator\\FHIRModelGenerator',
            'Ardenexal\\FHIRTools\\Component\\Serialization\\FHIRSerializationService',
            'Symfony\\Component\\Console\\Command\\Command',
            'PHPUnit\\Framework\\TestCase',
            'stdClass',
            '',
            'InvalidClassName',
        ];

        foreach ($nonModelsClasses as $className) {
            self::assertNull(
                VersionDetector::detectVersionFromClassName($className),
                "Should return null for non-Models class: {$className}",
            );
        }
    }

    /**
     * Test that detectVersion works with object instances.
     */
    public function testDetectVersionWorksWithObjectInstances(): void
    {
        // Create a mock object to test with
        $mockObject = new class () {
            // This is a test object
        };

        // Since we can't change the actual class name of the object,
        // we'll test that the method calls detectVersionFromClassName correctly
        $detectedVersion = VersionDetector::detectVersion($mockObject);

        // The anonymous class won't match our pattern, so should return null
        self::assertNull($detectedVersion);
    }

    /**
     * Test that isModelsComponentClass correctly identifies Models component classes.
     */
    public function testIsModelsComponentClassIdentifiesModelsClasses(): void
    {
        // Test Models component classes
        $modelsClasses = [
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRPatient',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIRHumanName',
            'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Primitive\\FHIRString',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum\\FHIRAdministrativeGender',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource\\Patient\\FHIRPatientContact',
        ];

        foreach ($modelsClasses as $className) {
            self::assertTrue(
                VersionDetector::isModelsComponentClass($className),
                "Should identify as Models component class: {$className}",
            );
        }

        // Test non-Models component classes
        $nonModelsClasses = [
            'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Generator\\FHIRModelGenerator',
            'Ardenexal\\FHIRTools\\Component\\Serialization\\FHIRSerializationService',
            'Symfony\\Component\\Console\\Command\\Command',
            'PHPUnit\\Framework\\TestCase',
            'stdClass',
            '',
        ];

        foreach ($nonModelsClasses as $className) {
            self::assertFalse(
                VersionDetector::isModelsComponentClass($className),
                "Should not identify as Models component class: {$className}",
            );
        }
    }

    /**
     * Test that isVersionSpecificClass correctly identifies version-specific classes.
     */
    public function testIsVersionSpecificClassIdentifiesVersionSpecificClasses(): void
    {
        $r4Class  = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRPatient';
        $r4bClass = 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIRHumanName';
        $r5Class  = 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Primitive\\FHIRString';

        // Test R4 class
        self::assertTrue(VersionDetector::isVersionSpecificClass($r4Class, 'R4'));
        self::assertFalse(VersionDetector::isVersionSpecificClass($r4Class, 'R4B'));
        self::assertFalse(VersionDetector::isVersionSpecificClass($r4Class, 'R5'));

        // Test R4B class
        self::assertFalse(VersionDetector::isVersionSpecificClass($r4bClass, 'R4'));
        self::assertTrue(VersionDetector::isVersionSpecificClass($r4bClass, 'R4B'));
        self::assertFalse(VersionDetector::isVersionSpecificClass($r4bClass, 'R5'));

        // Test R5 class
        self::assertFalse(VersionDetector::isVersionSpecificClass($r5Class, 'R4'));
        self::assertFalse(VersionDetector::isVersionSpecificClass($r5Class, 'R4B'));
        self::assertTrue(VersionDetector::isVersionSpecificClass($r5Class, 'R5'));
    }

    /**
     * Test that getModelType correctly identifies model types.
     */
    public function testGetModelTypeIdentifiesModelTypes(): void
    {
        // Test Resource type
        $resourceClass = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRPatient';
        self::assertEquals('Resource', VersionDetector::getModelType($resourceClass));

        // Test DataType type
        $dataTypeClass = 'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIRHumanName';
        self::assertEquals('DataType', VersionDetector::getModelType($dataTypeClass));

        // Test Primitive type
        $primitiveClass = 'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Primitive\\FHIRString';
        self::assertEquals('Primitive', VersionDetector::getModelType($primitiveClass));

        // Test Enum type
        $enumClass = 'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum\\FHIRAdministrativeGender';
        self::assertEquals('Enum', VersionDetector::getModelType($enumClass));
    }

    /**
     * Test that getModelType returns null for non-Models classes.
     */
    public function testGetModelTypeReturnsNullForNonModelsClasses(): void
    {
        $nonModelsClasses = [
            'Ardenexal\\FHIRTools\\Component\\CodeGeneration\\Generator\\FHIRModelGenerator',
            'Ardenexal\\FHIRTools\\Component\\Serialization\\FHIRSerializationService',
            'Symfony\\Component\\Console\\Command\\Command',
            'stdClass',
            '',
        ];

        foreach ($nonModelsClasses as $className) {
            self::assertNull(
                VersionDetector::getModelType($className),
                "Should return null for non-Models class: {$className}",
            );
        }
    }

    /**
     * Test backbone element class detection.
     */
    public function testBackboneElementClassDetection(): void
    {
        $backboneClasses = [
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\Patient\\FHIRPatientContact',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource\\Observation\\FHIRObservationComponent',
            'Ardenexal\\FHIRTools\\Component\\Models\\R5\\Resource\\Patient\\FHIRPatientCommunication',
        ];

        foreach ($backboneClasses as $className) {
            // Should be identified as Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Should detect correct version
            if (str_contains($className, '\\R4\\')) {
                self::assertEquals('R4', VersionDetector::detectVersionFromClassName($className));
            } elseif (str_contains($className, '\\R4B\\')) {
                self::assertEquals('R4B', VersionDetector::detectVersionFromClassName($className));
            } elseif (str_contains($className, '\\R5\\')) {
                self::assertEquals('R5', VersionDetector::detectVersionFromClassName($className));
            }
        }
    }

    /**
     * Test edge cases with malformed class names.
     */
    public function testEdgeCasesWithMalformedClassNames(): void
    {
        $malformedClasses = [
            'Ardenexal\\FHIRTools\\Component\\Models\\',
            'Ardenexal\\FHIRTools\\Component\\Models\\InvalidVersion\\Resource\\FHIRPatient',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\InvalidType\\FHIRPatient',
            '\\\\\\Invalid\\\\\\',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\',
        ];

        foreach ($malformedClasses as $className) {
            // These should not throw exceptions
            $version   = VersionDetector::detectVersionFromClassName($className);
            $isModels  = VersionDetector::isModelsComponentClass($className);
            $modelType = VersionDetector::getModelType($className);

            // Assertions depend on the specific malformed pattern
            if (str_contains($className, '\\Component\\Models\\')) {
                self::assertTrue($isModels, "Should identify as Models component: {$className}");
            } else {
                self::assertFalse($isModels, "Should not identify as Models component: {$className}");
            }

            // Version detection should be null for invalid patterns
            if (!preg_match('/\\\\Component\\\\Models\\\\(R4B?|R5)\\\\/', $className)) {
                self::assertNull($version, "Should return null version for: {$className}");
            }
        }
    }

    /**
     * Test that all methods are static.
     */
    public function testAllMethodsAreStatic(): void
    {
        $reflection = new \ReflectionClass(VersionDetector::class);
        $methods    = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            self::assertTrue(
                $method->isStatic(),
                "Method {$method->getName()} should be static",
            );
        }
    }

    /**
     * Test case sensitivity in version detection.
     */
    public function testCaseSensitivityInVersionDetection(): void
    {
        // Test that lowercase versions are not detected
        $lowercaseClass = 'Ardenexal\\FHIRTools\\Component\\Models\\r4\\Resource\\FHIRPatient';
        self::assertNull(VersionDetector::detectVersionFromClassName($lowercaseClass));

        // Test that mixed case versions are not detected
        $mixedCaseClass = 'Ardenexal\\FHIRTools\\Component\\Models\\r4B\\Resource\\FHIRPatient';
        self::assertNull(VersionDetector::detectVersionFromClassName($mixedCaseClass));
    }

    /**
     * Test performance with many class name checks.
     */
    public function testPerformanceWithManyClassNames(): void
    {
        $classNames = [];
        for ($i = 0; $i < 1000; ++$i) {
            $classNames[] = "Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRTest{$i}";
        }

        $startTime = microtime(true);

        foreach ($classNames as $className) {
            VersionDetector::detectVersionFromClassName($className);
            VersionDetector::isModelsComponentClass($className);
            VersionDetector::getModelType($className);
        }

        $endTime       = microtime(true);
        $executionTime = $endTime - $startTime;

        // Should complete within reasonable time (less than 1 second for 1000 operations)
        self::assertLessThan(1.0, $executionTime, 'Version detection should be performant');
    }
}
