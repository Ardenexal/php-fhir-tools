<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Common\AbstractFHIRNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRResourceXmlNormalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Regression tests for XML primitive-with-extension deserialization.
 *
 * FHIR allows primitive elements to carry both a value attribute and child <extension>
 * elements in XML: <fieldName value="X"><extension url="..."/></fieldName>
 * They may also carry only extensions with no value attribute (data-absent pattern).
 *
 * Previously, the XML normalizers would return the raw XmlEncoder array
 * (e.g. ['extension' => [...]] or ['@value' => 'false', 'extension' => [...]]) when
 * assigning to a builtin PHP type (?bool, ?string, etc.), causing a TypeError.
 */
#[CoversClass(FHIRResourceXmlNormalizer::class)]
#[CoversClass(AbstractFHIRNormalizer::class)]
final class XmlPrimitiveWithExtensionTest extends TestCase
{
    private FHIRSerializationService $service;

    protected function setUp(): void
    {
        $this->service = FHIRSerializationService::createDefault();
    }

    /**
     * <active> with only extension children (data-absent pattern, no value attribute).
     * Reproduces the patient-value-present-bad / patient-value-alternative-* failures.
     *
     * Expected: $patient->active === null (no value present), no TypeError.
     */
    public function testPatientActiveBoolExtensionOnlyDeserializesToNull(): void
    {
        $xml = <<<'XML'
<Patient xmlns="http://hl7.org/fhir">
  <id value="value-present-bad"/>
  <active>
    <extension url="http://hl7.org/fhir/StructureDefinition/data-absent-reason">
      <valueCode value="unknown"/>
    </extension>
  </active>
</Patient>
XML;

        $patient = $this->service->deserialize($xml);

        self::assertInstanceOf(PatientResource::class, $patient);
        self::assertNull($patient->active, '<active> with extension-only and no value attribute must deserialize to null');
    }

    /**
     * <active value="false"> with both a value attribute and child extensions.
     * The scalar value must be extracted; extensions on a ?bool property are discarded.
     *
     * Expected: $patient->active === false, no TypeError.
     */
    public function testPatientActiveBoolWithValueAndExtensionExtractsBool(): void
    {
        $xml = <<<'XML'
<Patient xmlns="http://hl7.org/fhir">
  <id value="test-bool-ext"/>
  <active value="false">
    <extension url="http://example.com/ext">
      <valueString value="note"/>
    </extension>
  </active>
</Patient>
XML;

        $patient = $this->service->deserialize($xml);

        self::assertInstanceOf(PatientResource::class, $patient);
        self::assertFalse($patient->active, '<active value="false"> with extensions must deserialize to false');
    }

    /**
     * <language value="text/fhirpath"/> inside a valueExpression Extension.
     * Reproduces the q-bp / mr-m-simple-nossystem failures.
     *
     * Expression::$language is ?string with propertyKind:'primitive'.
     * Previously denormalizePrimitiveProperty returned ['@value' => '...'] for builtin types.
     *
     * Expected: expression language is 'text/fhirpath', no TypeError.
     */
    public function testExpressionLanguageStringPrimitiveExtractedFromXmlAttribute(): void
    {
        $xml = <<<'XML'
<Patient xmlns="http://hl7.org/fhir">
  <id value="test-expression"/>
  <extension url="http://example.com/expression-ext">
    <valueExpression>
      <language value="text/fhirpath"/>
      <expression value="Patient.id"/>
    </valueExpression>
  </extension>
</Patient>
XML;

        $patient = $this->service->deserialize($xml);

        self::assertInstanceOf(PatientResource::class, $patient);

        $extensions = (new \ReflectionProperty($patient, 'extension'))->getValue($patient);
        self::assertIsArray($extensions);
        self::assertNotEmpty($extensions, 'extension array must be populated');

        $ext       = $extensions[0];
        $valueExpr = (new \ReflectionProperty($ext, 'value'))->getValue($ext);
        self::assertNotNull($valueExpr, 'Extension::$value (valueExpression choice) must be set');

        $language = (new \ReflectionProperty($valueExpr, 'language'))->getValue($valueExpr);
        self::assertSame('text/fhirpath', $language, 'Expression::$language must be extracted from XML @value attribute');
    }
}
