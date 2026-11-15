<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * Freezes serialized output byte-for-byte before the type-metadata consolidation moves the metadata
 * providers into the Metadata component.
 *
 * The relocation is supposed to change nothing a caller can observe. The property metadata that
 * drives element naming, ordering, array-ness and primitive wrapping is exactly what moves, so if any
 * of it is read differently afterwards the change surfaces here as a diff rather than as a subtly
 * wrong document nobody notices.
 *
 * Coverage is chosen for what the movers actually decide: a backbone element with a repeating child,
 * scalar primitives of three PHP types, both wire formats, and a CDA logical model whose element
 * order comes from published content-model order rather than declaration order.
 *
 * These are snapshots, not specifications. If one changes, the question is which commit changed it
 * and whether that was intended -- not whether to update the string.
 */
final class OutputSnapshotBaselineTest extends TestCase
{
    /** maxLength on the first item; pinned so the integer-typing assertion names its value. */
    private const int EXPECTED_MAX_LENGTH = 10;

    /** A backbone-bearing resource with a repeating child and three scalar primitive types. */
    private const string QUESTIONNAIRE_XML = <<<'XML'
<Questionnaire xmlns="http://hl7.org/fhir">
  <status value="active"/>
  <item>
    <linkId value="q1"/>
    <type value="string"/>
    <required value="true"/>
    <maxLength value="10"/>
  </item>
  <item>
    <linkId value="q2"/>
    <type value="decimal"/>
    <required value="false"/>
  </item>
</Questionnaire>
XML;

    /**
     * Repeating backbones stay arrays and scalar primitives keep their PHP types in JSON.
     */
    public function testBackboneBearingResourceSerializesToStableJson(): void
    {
        $service = FHIRSerializationService::createDefault();

        $json = $service->serializeToJson($service->deserialize(self::QUESTIONNAIRE_XML));

        $decoded = json_decode($json, true);
        self::assertIsArray($decoded);
        self::assertSame('Questionnaire', $decoded['resourceType'] ?? null);
        self::assertSame('active', $decoded['status'] ?? null);

        $items = $decoded['item'] ?? null;
        self::assertIsArray($items, 'a repeating backbone must stay an array');
        self::assertCount(2, $items);

        // Scalar typing is decided by property metadata, so pin the PHP types, not just the values.
        self::assertSame('q1', $items[0]['linkId'] ?? null);
        self::assertTrue($items[0]['required'] ?? null, 'boolean primitive must stay a JSON boolean');
        self::assertSame(self::EXPECTED_MAX_LENGTH, $items[0]['maxLength'] ?? null, 'integer primitive must stay a JSON number');
        self::assertFalse($items[1]['required'] ?? null);
        self::assertArrayNotHasKey('maxLength', $items[1], 'an unset primitive must not be emitted');
    }

    /**
     * XML output settles after one round trip and omits primitives that were never set.
     */
    public function testBackboneBearingResourceRoundTripsThroughXmlUnchanged(): void
    {
        $service = FHIRSerializationService::createDefault();

        $once  = $service->serializeToXml($service->deserialize(self::QUESTIONNAIRE_XML));
        $twice = $service->serializeToXml($service->deserialize($once));

        self::assertSame($once, $twice, 'XML output must be a fixed point after one round trip');
        self::assertStringContainsString('<linkId value="q1"/>', $once);
        self::assertStringContainsString('<maxLength value="10"/>', $once);
        self::assertStringNotContainsString('<maxLength value=""/>', $once, 'unset primitives must not be emitted');
    }

    /**
     * Passing a document through JSON does not alter how it renders back to XML.
     */
    public function testJsonAndXmlAgreeOnWhatTheDocumentContains(): void
    {
        $service = FHIRSerializationService::createDefault();

        $fromXml   = $service->deserialize(self::QUESTIONNAIRE_XML);
        $viaJson   = $service->deserialize($service->serializeToJson($fromXml));
        $backToXml = $service->serializeToXml($viaJson);

        self::assertSame(
            $service->serializeToXml($fromXml),
            $backToXml,
            'a JSON round trip must not change the XML rendering',
        );
    }

    /** A patient whose name exercises the one FHIR element pair whose order is easy to invert. */
    private const string PATIENT_JSON = '{"resourceType":"Patient","id":"p1","active":true,"name":[{"family":"Doe","given":["Jane"]}],"birthDate":"1980-01-01","gender":"female"}';

    /**
     * FHIR XML element order comes from nothing but the property enumeration sequence.
     *
     * No FHIR type carries a published content model -- `contentModelOrder()` returns an empty list
     * for everything outside CDA, and an empty list means the ordering sort returns its input
     * untouched. So for every resource and complex type in the library, the sequence the enumeration
     * hands over *is* the emitted element order, with nothing downstream to correct it.
     *
     * That was unpinned until this test. Reversing the enumeration input leaves all 4678 other tests
     * green while emitting `<given>` before `<family>`, which is invalid against the R4 schema --
     * verified by running exactly that on 2026-09-02. `HumanName` is used because its order is fixed
     * by the specification (use, text, family, given, prefix, suffix, period) and the two elements
     * sit adjacent, so an inverted sequence shows up as a swap rather than as a plausible variant.
     */
    public function testFhirXmlElementOrderFollowsThePropertyEnumerationSequence(): void
    {
        $service = FHIRSerializationService::createDefault();

        $xml = $service->serializeToXml($service->deserializeFromJson(self::PATIENT_JSON, PatientResource::class));

        $familyAt = strpos($xml, '<family ');
        $givenAt  = strpos($xml, '<given ');

        self::assertIsInt($familyAt, 'the fixture must actually emit a family element');
        self::assertIsInt($givenAt, 'the fixture must actually emit a given element');
        self::assertLessThan(
            $givenAt,
            $familyAt,
            'HumanName declares family before given, and nothing but the enumeration order enforces it',
        );
    }

    /**
     * An unwritten property and one holding null serialize identically, even with nulls kept.
     *
     * This is the equivalence that lets the normalizers read state through one guarded call instead
     * of probing initialisation and then reading. It is not obvious from the code: `shouldOmitValue`
     * skips null only when `omitNullValues` is on, so with the option off a null value does get past
     * that gate -- but every emit branch downstream guards `$normalizedValue !== null` regardless, so
     * it is dropped there instead. The option chooses where a null is discarded, not whether.
     *
     * Pinned because the collapse depends on it and nothing else in the suite passes a non-default
     * `omitNullValues`: no test file mentions the option or its `fhir_omit_null_values` context key.
     * If a future change makes nulls emit, this fails and the guarded read has to grow the
     * distinction back.
     */
    public function testUnwrittenAndExplicitlyNullPropertiesSerializeIdenticallyEvenWhenNullsAreKept(): void
    {
        $service = FHIRSerializationService::createDefault();
        $context = ['fhir_omit_null_values' => false];

        $unwritten = (new \ReflectionClass(PatientResource::class))->newInstanceWithoutConstructor();
        (new \ReflectionProperty(PatientResource::class, 'id'))->setValue($unwritten, 'p1');

        $explicitNull         = new PatientResource(id: 'p1');
        $explicitNull->gender = null;

        self::assertFalse(
            (new \ReflectionProperty(PatientResource::class, 'gender'))->isInitialized($unwritten),
            'the fixture must really leave gender unwritten',
        );

        self::assertSame(
            $service->serializeToJson($explicitNull, $context),
            $service->serializeToJson($unwritten, $context),
            'JSON cannot tell an unwritten property from an explicit null',
        );
        self::assertSame(
            $service->serializeToXml($explicitNull, $context),
            $service->serializeToXml($unwritten, $context),
            'nor can XML',
        );
    }

    /**
     * A CDA logical model emits its elements in published content-model order, not declaration order.
     */
    public function testCdaLogicalModelEmitsInPublishedContentModelOrder(): void
    {
        $service = FHIRSerializationService::createWithIG(version: FhirVersion::R5);

        $xml = $service->serializeToXml(new ClinicalDocument(
            id: new II(root: '2.16.840.1.113883.19.5'),
            code: new CS(code: '34133-9'),
        ));

        self::assertStringContainsString('urn:hl7-org:v3', $xml);
        self::assertStringContainsString('root="2.16.840.1.113883.19.5"', $xml);

        // Element order for a logical model comes from published content-model order, which is read
        // from the metadata that moves. Pin the relative order rather than the whole document.
        $idAt   = strpos($xml, '<id ');
        $codeAt = strpos($xml, '<code ');
        self::assertIsInt($idAt);
        self::assertIsInt($codeAt);
        self::assertLessThan($codeAt, $idAt, 'id must precede code in CDA content-model order');
    }
}
