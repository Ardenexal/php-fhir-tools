<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Models;

use Ardenexal\FHIRTools\Component\Models\Exception\ModelsException;
use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for ModelRegistry utility class.
 *
 * Tests specific examples and edge cases for the ModelRegistry functionality.
 */
class ModelRegistryTest extends TestCase
{
    /**
     * Test that getResourceClass returns correct class names for supported versions.
     */
    public function testGetResourceClassReturnsCorrectClassNames(): void
    {
        // Test R4 resource class
        $r4PatientClass = ModelRegistry::getResourceClass('R4', 'Patient');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRPatient', $r4PatientClass);

        // Test R4B resource class
        $r4bObservationClass = ModelRegistry::getResourceClass('R4B', 'Observation');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource\\FHIRObservation', $r4bObservationClass);

        // Test R5 resource class
        $r5PractitionerClass = ModelRegistry::getResourceClass('R5', 'Practitioner');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\Resource\\FHIRPractitioner', $r5PractitionerClass);
    }

    /**
     * Test that getBackboneElementClass returns correct class names.
     */
    public function testGetBackboneElementClassReturnsCorrectClassNames(): void
    {
        // Test R4 backbone element class
        $r4ContactClass = ModelRegistry::getBackboneElementClass('R4', 'Patient', 'Contact');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\Patient\\FHIRPatientContact', $r4ContactClass);

        // Test R4B backbone element class
        $r4bComponentClass = ModelRegistry::getBackboneElementClass('R4B', 'Observation', 'Component');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource\\Observation\\FHIRObservationComponent', $r4bComponentClass);

        // Test R5 backbone element class
        $r5CommunicationClass = ModelRegistry::getBackboneElementClass('R5', 'Patient', 'Communication');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\Resource\\Patient\\FHIRPatientCommunication', $r5CommunicationClass);
    }

    /**
     * Test that getDataTypeClass returns correct class names.
     */
    public function testGetDataTypeClassReturnsCorrectClassNames(): void
    {
        // Test R4 data type class
        $r4HumanNameClass = ModelRegistry::getDataTypeClass('R4', 'HumanName');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\FHIRHumanName', $r4HumanNameClass);

        // Test R4B data type class
        $r4bAddressClass = ModelRegistry::getDataTypeClass('R4B', 'Address');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIRAddress', $r4bAddressClass);

        // Test R5 data type class
        $r5ContactPointClass = ModelRegistry::getDataTypeClass('R5', 'ContactPoint');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\DataType\\FHIRContactPoint', $r5ContactPointClass);
    }

    /**
     * Test that getPrimitiveClass returns correct class names.
     */
    public function testGetPrimitiveClassReturnsCorrectClassNames(): void
    {
        // Test R4 primitive class
        $r4StringClass = ModelRegistry::getPrimitiveClass('R4', 'String');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Primitive\\FHIRString', $r4StringClass);

        // Test R4B primitive class
        $r4bIntegerClass = ModelRegistry::getPrimitiveClass('R4B', 'Integer');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Primitive\\FHIRInteger', $r4bIntegerClass);

        // Test R5 primitive class
        $r5BooleanClass = ModelRegistry::getPrimitiveClass('R5', 'Boolean');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\Primitive\\FHIRBoolean', $r5BooleanClass);
    }

    /**
     * Test that getEnumClass returns correct class names.
     */
    public function testGetEnumClassReturnsCorrectClassNames(): void
    {
        // Test R4 enum class
        $r4GenderClass = ModelRegistry::getEnumClass('R4', 'AdministrativeGender');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum\\FHIRAdministrativeGender', $r4GenderClass);

        // Test R4B enum class
        $r4bStatusClass = ModelRegistry::getEnumClass('R4B', 'ObservationStatus');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Enum\\FHIRObservationStatus', $r4bStatusClass);

        // Test R5 enum class
        $r5SystemClass = ModelRegistry::getEnumClass('R5', 'ContactPointSystem');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\Enum\\FHIRContactPointSystem', $r5SystemClass);
    }

    /**
     * Test that getCodeTypeClass returns correct class names.
     */
    public function testGetCodeTypeClassReturnsCorrectClassNames(): void
    {
        // Test R4 code type class
        $r4GenderTypeClass = ModelRegistry::getCodeTypeClass('R4', 'AdministrativeGender');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\FHIRAdministrativeGenderType', $r4GenderTypeClass);

        // Test R4B code type class
        $r4bStatusTypeClass = ModelRegistry::getCodeTypeClass('R4B', 'ObservationStatus');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIRObservationStatusType', $r4bStatusTypeClass);

        // Test R5 code type class
        $r5SystemTypeClass = ModelRegistry::getCodeTypeClass('R5', 'ContactPointSystem');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\DataType\\FHIRContactPointSystemType', $r5SystemTypeClass);
    }

    /**
     * Test that unsupported versions throw ModelsException.
     */
    public function testUnsupportedVersionsThrowException(): void
    {
        $this->expectException(ModelsException::class);
        $this->expectExceptionMessage('Unsupported FHIR version: R3. Supported versions: R4, R4B, R5');

        ModelRegistry::getResourceClass('R3', 'Patient');
    }

    /**
     * Test that all methods throw exception for unsupported versions.
     */
    public function testAllMethodsThrowExceptionForUnsupportedVersions(): void
    {
        $unsupportedVersions = ['R3', 'R6', 'DSTU2', 'STU3', 'invalid'];

        foreach ($unsupportedVersions as $version) {
            // Test getResourceClass
            try {
                ModelRegistry::getResourceClass($version, 'Patient');
                self::fail("Expected ModelsException for version {$version} in getResourceClass");
            } catch (ModelsException $e) {
                self::assertStringContainsString("Unsupported FHIR version: {$version}", $e->getMessage());
            }

            // Test getBackboneElementClass
            try {
                ModelRegistry::getBackboneElementClass($version, 'Patient', 'Contact');
                self::fail("Expected ModelsException for version {$version} in getBackboneElementClass");
            } catch (ModelsException $e) {
                self::assertStringContainsString("Unsupported FHIR version: {$version}", $e->getMessage());
            }

            // Test getDataTypeClass
            try {
                ModelRegistry::getDataTypeClass($version, 'HumanName');
                self::fail("Expected ModelsException for version {$version} in getDataTypeClass");
            } catch (ModelsException $e) {
                self::assertStringContainsString("Unsupported FHIR version: {$version}", $e->getMessage());
            }

            // Test getPrimitiveClass
            try {
                ModelRegistry::getPrimitiveClass($version, 'String');
                self::fail("Expected ModelsException for version {$version} in getPrimitiveClass");
            } catch (ModelsException $e) {
                self::assertStringContainsString("Unsupported FHIR version: {$version}", $e->getMessage());
            }

            // Test getEnumClass
            try {
                ModelRegistry::getEnumClass($version, 'AdministrativeGender');
                self::fail("Expected ModelsException for version {$version} in getEnumClass");
            } catch (ModelsException $e) {
                self::assertStringContainsString("Unsupported FHIR version: {$version}", $e->getMessage());
            }

            // Test getCodeTypeClass
            try {
                ModelRegistry::getCodeTypeClass($version, 'AdministrativeGender');
                self::fail("Expected ModelsException for version {$version} in getCodeTypeClass");
            } catch (ModelsException $e) {
                self::assertStringContainsString("Unsupported FHIR version: {$version}", $e->getMessage());
            }
        }
    }

    /**
     * Test that isSupportedVersion works correctly.
     */
    public function testIsSupportedVersionWorksCorrectly(): void
    {
        // Test supported versions
        self::assertTrue(ModelRegistry::isSupportedVersion('R4'));
        self::assertTrue(ModelRegistry::isSupportedVersion('R4B'));
        self::assertTrue(ModelRegistry::isSupportedVersion('R5'));

        // Test unsupported versions
        self::assertFalse(ModelRegistry::isSupportedVersion('R3'));
        self::assertFalse(ModelRegistry::isSupportedVersion('R6'));
        self::assertFalse(ModelRegistry::isSupportedVersion('DSTU2'));
        self::assertFalse(ModelRegistry::isSupportedVersion('STU3'));
        self::assertFalse(ModelRegistry::isSupportedVersion('invalid'));
        self::assertFalse(ModelRegistry::isSupportedVersion(''));
    }

    /**
     * Test that getSupportedVersions returns correct array.
     */
    public function testGetSupportedVersionsReturnsCorrectArray(): void
    {
        $supportedVersions = ModelRegistry::getSupportedVersions();

        self::assertEquals(['R4', 'R4B', 'R5'], $supportedVersions);
        self::assertCount(3, $supportedVersions);
        self::assertContains('R4', $supportedVersions);
        self::assertContains('R4B', $supportedVersions);
        self::assertContains('R5', $supportedVersions);
    }

    /**
     * Test edge cases with empty or special characters in model names.
     */
    public function testEdgeCasesWithModelNames(): void
    {
        // Test with empty model name
        $emptyNameClass = ModelRegistry::getResourceClass('R4', '');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIR', $emptyNameClass);

        // Test with model name containing special characters
        $specialCharClass = ModelRegistry::getResourceClass('R4', 'Test_Model-Name');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIRTest_Model-Name', $specialCharClass);

        // Test with numeric model name
        $numericClass = ModelRegistry::getResourceClass('R4', '123');
        self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIR123', $numericClass);
    }

    /**
     * Test case sensitivity of version parameter.
     */
    public function testVersionCaseSensitivity(): void
    {
        // Test that lowercase versions are not supported
        $this->expectException(ModelsException::class);
        ModelRegistry::getResourceClass('r4', 'Patient');
    }

    /**
     * Test that all methods are static.
     */
    public function testAllMethodsAreStatic(): void
    {
        $reflection = new \ReflectionClass(ModelRegistry::class);
        $methods    = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            self::assertTrue(
                $method->isStatic(),
                "Method {$method->getName()} should be static",
            );
        }
    }
}
