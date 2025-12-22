<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Test cases for FHIRMetadataExtractor
 *
 * @author Kiro AI Assistant
 */
class FHIRMetadataExtractorTest extends TestCase
{
    private FHIRMetadataExtractor $extractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extractor = new FHIRMetadataExtractor();
    }

    public function testExtractResourceTypeFromResourceClass()
    {
        $patient = new TestPatientResource();

        $resourceType = $this->extractor->extractResourceType($patient);

        self::assertSame('Patient', $resourceType);
    }

    public function testExtractResourceTypeFromNonResourceClass()
    {
        $address = new TestAddressComplexType();

        $resourceType = $this->extractor->extractResourceType($address);

        self::assertNull($resourceType);
    }

    public function testExtractFHIRTypeFromResourceClass()
    {
        $patient = new TestPatientResource();

        $fhirType = $this->extractor->extractFHIRType($patient);

        self::assertSame('Patient', $fhirType);
    }

    public function testExtractFHIRTypeFromComplexTypeClass()
    {
        $address = new TestAddressComplexType();

        $fhirType = $this->extractor->extractFHIRType($address);

        self::assertSame('Address', $fhirType);
    }

    public function testExtractFHIRTypeFromPrimitiveTypeClass()
    {
        $string = new TestStringPrimitive();

        $fhirType = $this->extractor->extractFHIRType($string);

        self::assertSame('string', $fhirType);
    }

    public function testExtractFHIRTypeFromBackboneElementClass()
    {
        $contact = new TestPatientContactBackbone();

        $fhirType = $this->extractor->extractFHIRType($contact);

        self::assertSame('Patient.contact', $fhirType);
    }

    public function testIsResourceReturnsTrueForResourceClass()
    {
        $patient = new TestPatientResource();

        $isResource = $this->extractor->isResource($patient);

        self::assertTrue($isResource);
    }

    public function testIsResourceReturnsFalseForNonResourceClass()
    {
        $address = new TestAddressComplexType();

        $isResource = $this->extractor->isResource($address);

        self::assertFalse($isResource);
    }

    public function testIsComplexTypeReturnsTrueForComplexTypeClass()
    {
        $address = new TestAddressComplexType();

        $isComplexType = $this->extractor->isComplexType($address);

        self::assertTrue($isComplexType);
    }

    public function testIsComplexTypeReturnsFalseForNonComplexTypeClass()
    {
        $patient = new TestPatientResource();

        $isComplexType = $this->extractor->isComplexType($patient);

        self::assertFalse($isComplexType);
    }

    public function testIsPrimitiveTypeReturnsTrueForPrimitiveTypeClass()
    {
        $string = new TestStringPrimitive();

        $isPrimitive = $this->extractor->isPrimitiveType($string);

        self::assertTrue($isPrimitive);
    }

    public function testIsPrimitiveTypeReturnsFalseForNonPrimitiveTypeClass()
    {
        $patient = new TestPatientResource();

        $isPrimitive = $this->extractor->isPrimitiveType($patient);

        self::assertFalse($isPrimitive);
    }

    public function testIsBackboneElementReturnsTrueForBackboneElementClass()
    {
        $contact = new TestPatientContactBackbone();

        $isBackbone = $this->extractor->isBackboneElement($contact);

        self::assertTrue($isBackbone);
    }

    public function testIsBackboneElementReturnsFalseForNonBackboneElementClass()
    {
        $patient = new TestPatientResource();

        $isBackbone = $this->extractor->isBackboneElement($patient);

        self::assertFalse($isBackbone);
    }

    public function testExtractFHIRVersionFromResourceClass()
    {
        $patient = new TestPatientResource();

        $version = $this->extractor->extractFHIRVersion($patient);

        self::assertSame('R4B', $version);
    }

    public function testExtractFHIRVersionFromComplexTypeClass()
    {
        $address = new TestAddressComplexType();

        $version = $this->extractor->extractFHIRVersion($address);

        self::assertSame('R4B', $version);
    }

    public function testExtractParentResourceFromBackboneElement()
    {
        $contact = new TestPatientContactBackbone();

        $parentResource = $this->extractor->extractParentResource($contact);

        self::assertSame('Patient', $parentResource);
    }

    public function testExtractParentResourceFromNonBackboneElement()
    {
        $patient = new TestPatientResource();

        $parentResource = $this->extractor->extractParentResource($patient);

        self::assertNull($parentResource);
    }

    public function testExtractElementPathFromBackboneElement()
    {
        $contact = new TestPatientContactBackbone();

        $elementPath = $this->extractor->extractElementPath($contact);

        self::assertSame('Patient.contact', $elementPath);
    }

    public function testExtractElementPathFromNonBackboneElement()
    {
        $patient = new TestPatientResource();

        $elementPath = $this->extractor->extractElementPath($patient);

        self::assertNull($elementPath);
    }

    public function testCachingBehavior()
    {
        $patient = new TestPatientResource();

        // First call should populate cache
        $resourceType1 = $this->extractor->extractResourceType($patient);

        // Second call should use cache
        $resourceType2 = $this->extractor->extractResourceType($patient);

        self::assertSame($resourceType1, $resourceType2);
        self::assertSame('Patient', $resourceType1);

        // Verify cache has entries
        $cache = $this->extractor->getCache();
        self::assertFalse($cache->isEmpty());
    }

    public function testClearCache()
    {
        $patient = new TestPatientResource();

        // Populate cache
        $this->extractor->extractResourceType($patient);

        // Verify cache has entries
        $cache = $this->extractor->getCache();
        self::assertFalse($cache->isEmpty());

        // Clear cache
        $this->extractor->clearCache();

        // Verify cache is empty
        self::assertTrue($cache->isEmpty());
    }

    public function testHandlesClassWithoutAttributes()
    {
        $plain = new PlainClass();

        self::assertNull($this->extractor->extractResourceType($plain));
        self::assertNull($this->extractor->extractFHIRType($plain));
        self::assertFalse($this->extractor->isResource($plain));
        self::assertFalse($this->extractor->isComplexType($plain));
        self::assertFalse($this->extractor->isPrimitiveType($plain));
        self::assertFalse($this->extractor->isBackboneElement($plain));
        self::assertNull($this->extractor->extractFHIRVersion($plain));
        self::assertNull($this->extractor->extractParentResource($plain));
        self::assertNull($this->extractor->extractElementPath($plain));
    }
}

// Test classes with FHIR attributes

#[FhirResource(
    type: 'Patient',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/Patient',
    fhirVersion: 'R4B',
)]
class TestPatientResource
{
}

#[FHIRComplexType(typeName: 'Address', fhirVersion: 'R4B')]
class TestAddressComplexType
{
}

#[FHIRPrimitive(primitiveType: 'string', fhirVersion: 'R4B')]
class TestStringPrimitive
{
}

#[FHIRBackboneElement(
    parentResource: 'Patient',
    elementPath: 'Patient.contact',
    fhirVersion: 'R4B',
)]
class TestPatientContactBackbone
{
}

// Plain class without attributes
class PlainClass
{
}
