<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Test XML primitive value validation.
 *
 * FHIR XML spec requires primitive values to use attributes (value="X"),
 * not child elements (<value>X</value>). This test ensures we enforce that.
 */
final class XMLPrimitiveValidationTest extends TestCase
{
    private FHIRSerializationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = FHIRSerializationService::createDefault();
    }

    /**
     * Test that non-standard child element format is rejected with clear error.
     */
    public function testRejectsChildElementFormat(): void
    {
        $invalidXml = <<<'XML'
<?xml version="1.0"?>
<Patient xmlns="http://hl7.org/fhir">
  <birthDate>
    <value>1974-12-25</value>
  </birthDate>
</Patient>
XML;

        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessage('Invalid FHIR XML format: Primitive values must use attributes');

        $this->service->deserialize($invalidXml);
    }

    /**
     * Test that correct attribute format is accepted.
     */
    public function testAcceptsAttributeFormat(): void
    {
        $validXml = <<<'XML'
<?xml version="1.0"?>
<Patient xmlns="http://hl7.org/fhir">
  <birthDate value="1974-12-25"/>
</Patient>
XML;

        $patient = $this->service->deserialize($validXml);

        self::assertNotNull($patient);

        $reflection = new \ReflectionClass($patient);
        self::assertTrue($reflection->hasProperty('birthDate'));

        $birthDateProp = $reflection->getProperty('birthDate');
        $birthDate     = $birthDateProp->getValue($patient);
        self::assertIsObject($birthDate);

        $bdRefl    = new \ReflectionClass($birthDate);
        $valueProp = $bdRefl->getProperty('value');
        self::assertEquals('1974-12-25', $valueProp->getValue($birthDate));
    }

    /**
     * Test that primitives with extensions work correctly.
     */
    public function testAcceptsAttributeFormatWithExtensions(): void
    {
        $validXml = <<<'XML'
<?xml version="1.0"?>
<Patient xmlns="http://hl7.org/fhir">
  <birthDate value="1974-12-25">
    <extension url="http://hl7.org/fhir/StructureDefinition/patient-birthTime">
      <valueDateTime value="1974-12-25T14:35:45-05:00"/>
    </extension>
  </birthDate>
</Patient>
XML;

        $patient = $this->service->deserialize($validXml);

        self::assertNotNull($patient);

        $reflection    = new \ReflectionClass($patient);
        $birthDateProp = $reflection->getProperty('birthDate');
        $birthDate     = $birthDateProp->getValue($patient);

        self::assertIsObject($birthDate);

        $bdRefl = new \ReflectionClass($birthDate);

        // Check value
        $valueProp = $bdRefl->getProperty('value');
        self::assertEquals('1974-12-25', $valueProp->getValue($birthDate));

        // Check extensions
        $extProp    = $bdRefl->getProperty('extension');
        $extensions = $extProp->getValue($birthDate);
        self::assertIsArray($extensions);
        self::assertCount(1, $extensions);
    }
}
