<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuControlAct;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuEntry;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Patient;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TEL;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\PostalAddressUse;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * CDA logical-model XML serialization (Milestone 5).
 *
 * Validates the riskiest assumptions of the CDA serializer: a #[LogicalModel] class routes to the
 * CDA XML normalizer, the document root declares the urn:hl7-org:v3 namespace, and #[FhirProperty]
 * xmlAttr properties (classCode/moodCode/root) emit as XML attributes rather than child elements.
 */
final class CdaXmlSerializationTest extends TestCase
{
    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    public function testClinicalDocumentRootDeclaresV3Namespace(): void
    {
        $document = new ClinicalDocument(
            id: new II(root: '2.16.840.1.113883.19.5'),
        );

        $xml = $this->service()->serializeToXml($document);

        self::assertStringContainsString('<ClinicalDocument', $xml);
        self::assertStringContainsString('xmlns="urn:hl7-org:v3"', $xml);
    }

    public function testActAttributesEmitAsXmlAttributes(): void
    {
        $document = new ClinicalDocument(
            id: new II(root: '2.16.840.1.113883.19.5'),
        );

        $xml = $this->service()->serializeToXml($document);

        self::assertStringContainsString('classCode="DOCCLIN"', $xml);
        self::assertStringContainsString('moodCode="EVN"', $xml);
        // classCode must be an attribute on the root, never a child element.
        self::assertStringNotContainsString('<classCode', $xml);
    }

    public function testNestedDatatypeAttributesEmitAndNamespaceNotRedeclared(): void
    {
        $document = new ClinicalDocument(
            id: new II(root: '2.16.840.1.113883.19.5'),
        );

        $xml = $this->service()->serializeToXml($document);

        // II.root is an xmlAttr on the nested <id> element.
        self::assertStringContainsString('root="2.16.840.1.113883.19.5"', $xml);
        // The v3 namespace is declared once (on the root), not re-declared on nested elements.
        self::assertSame(1, substr_count($xml, 'xmlns="urn:hl7-org:v3"'));
    }

    public function testJsonSerializationThrowsDescriptiveException(): void
    {
        $document = new ClinicalDocument(
            id: new II(root: '2.16.840.1.113883.19.5'),
        );

        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/XML-only|serializeToXml/');

        $this->service()->serializeToJson($document);
    }

    public function testSdtcPropertyEmitsUnderSdtcNamespaceWithPrefixStripped(): void
    {
        $document = new ClinicalDocument(
            id: new II(root: '2.16.840.1.113883.19.5'),
            sdtcStatusCode: new CS(code: 'active'),
        );

        $xml = $this->service()->serializeToXml($document);

        // sdtcStatusCode -> <statusCode xmlns="urn:hl7-org:sdtc"> (prefix stripped, sdtc namespace).
        self::assertStringContainsString('<statusCode xmlns="urn:hl7-org:sdtc"', $xml);
        self::assertStringContainsString('code="active"', $xml);
        self::assertStringNotContainsString('sdtcStatusCode', $xml);
    }

    public function testAuExtensionElementEmitsUnderAdhaNamespace(): void
    {
        $entry = new AuEntry(
            controlAct: new AuControlAct(classCode: 'ACT', moodCode: 'EVN'),
        );

        $xml = $this->service()->serializeToXml($entry);

        self::assertStringContainsString(
            '<controlAct xmlns="http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0"',
            $xml,
        );
    }

    /**
     * nullFlavor is declared on ANY, the root of the whole CDA datatype lattice, as an enum-typed
     * xmlAttr. A BackedEnum is an object, so the xmlAttr emit branch must unwrap ->value rather than
     * fall through to the generic normalizer chain (which has no enum normalizer and throws).
     */
    public function testEnumAttributeSerializesAsItsBackingCode(): void
    {
        $xml = $this->service()->serializeToXml(new II(root: '1.2.3', nullFlavor: NullFlavor::ni));

        self::assertStringContainsString('nullFlavor="NI"', $xml);
        self::assertStringNotContainsString('<nullFlavor', $xml);
    }

    public function testEnumAttributeRoundTripsToTheEnumCase(): void
    {
        $document = $this->service()->deserializeFromXml(
            '<ClinicalDocument xmlns="urn:hl7-org:v3"><id nullFlavor="NI" /></ClinicalDocument>',
            ClinicalDocument::class,
        );

        self::assertSame(NullFlavor::ni, $document->id?->nullFlavor);
    }

    /**
     * V3 SET<cs> attributes (AD.use, EN.use, ENXP.qualifier) carry several codes in one attribute,
     * space-delimited.
     */
    public function testEnumListAttributeSerializesSpaceDelimited(): void
    {
        $xml = $this->service()->serializeToXml(new AD(use: [PostalAddressUse::hp, PostalAddressUse::wp]));

        self::assertStringContainsString('use="HP WP"', $xml);
    }

    public function testEnumListAttributeRoundTripsPreservingOrder(): void
    {
        $address = $this->service()->deserializeFromXml(
            '<AD xmlns="urn:hl7-org:v3" use="HP WP" />',
            AD::class,
        );

        self::assertSame([PostalAddressUse::hp, PostalAddressUse::wp], $address->use);
    }

    /**
     * TEL.use is a list<string> xmlAttr with no bound enum, so the same array branch must split it
     * into plain strings rather than assigning the raw attribute value to an array property.
     */
    public function testNonEnumListAttributeRoundTripsAsStrings(): void
    {
        $tel = $this->service()->deserializeFromXml(
            '<TEL xmlns="urn:hl7-org:v3" use="HP WP" />',
            TEL::class,
        );

        self::assertSame(['HP', 'WP'], $tel->use);
    }

    /**
     * A code the generated enum does not carry must fail loudly and catchably, never be dropped:
     * silent loss is what makes malformed CDA undetectable downstream.
     */
    public function testUnknownEnumCodeThrowsDescriptiveException(): void
    {
        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/NOT_A_REAL_CODE/');

        $this->service()->deserializeFromXml(
            '<AD xmlns="urn:hl7-org:v3" use="NOT_A_REAL_CODE" />',
            AD::class,
        );
    }

    /**
     * The emit side strips the sdtc prefix (sdtcStatusCode -> <statusCode xmlns="urn:hl7-org:sdtc">),
     * so the denormalizer needs the matching inverse. Without it the element resolves to no property
     * and is dropped by the unmatched-element continue, losing the value with no signal.
     */
    public function testSdtcElementDeserializesToItsPrefixedProperty(): void
    {
        $document = $this->service()->deserializeFromXml(
            '<ClinicalDocument xmlns="urn:hl7-org:v3">'
            . '<statusCode xmlns="urn:hl7-org:sdtc" code="active" />'
            . '</ClinicalDocument>',
            ClinicalDocument::class,
        );

        self::assertSame('active', $document->sdtcStatusCode?->code);
    }

    public function testSdtcElementSurvivesAFullRoundTrip(): void
    {
        $service = $this->service();
        $xml     = $service->serializeToXml(new ClinicalDocument(
            id: new II(root: '2.16.840.1.113883.19.5'),
            sdtcStatusCode: new CS(code: 'active'),
        ));

        self::assertSame($xml, $service->serializeToXml($service->deserializeFromXml($xml, ClinicalDocument::class)));
    }

    /**
     * Patient declares both a core raceCode (v3) and an sdtcRaceCode that emits under the same local
     * name in the sdtc namespace. Symfony's XmlEncoder decode is namespace-blind, so both collapse
     * into one grouped array key and only the source DOM can tell them apart. Three CDA classes hit
     * this: Patient (raceCode, ethnicGroupCode) and CustodianOrganization (telecom).
     */
    public function testCollidingCoreAndSdtcElementsDeserializeToSeparateProperties(): void
    {
        $patient = $this->service()->deserializeFromXml(
            '<Patient xmlns="urn:hl7-org:v3">'
            . '<raceCode code="V3RACE" />'
            . '<raceCode xmlns="urn:hl7-org:sdtc" code="SDTCRACE" />'
            . '</Patient>',
            Patient::class,
        );

        self::assertSame('V3RACE', $patient->raceCode?->code, 'the core v3 raceCode must keep its own value');
        self::assertCount(1, $patient->sdtcRaceCode);
        self::assertInstanceOf(CE::class, $patient->sdtcRaceCode[0]);
        self::assertSame('SDTCRACE', $patient->sdtcRaceCode[0]->code);
    }

    /**
     * Both properties emit under the local name `raceCode`, differing only by namespace, so a naive
     * keyed write makes the second silently overwrite the first. XmlEncoder renders a list of maps
     * as repeated siblings and honours a per-item @xmlns, so both must survive as separate elements.
     */
    public function testCollidingCoreAndSdtcElementsBothSerialize(): void
    {
        $xml = $this->service()->serializeToXml(new Patient(
            raceCode: new CE(code: 'V3RACE'),
            sdtcRaceCode: [new CE(code: 'SDTCRACE')],
        ));

        self::assertSame(2, substr_count($xml, '<raceCode'), 'both raceCode elements must be emitted');
        self::assertStringContainsString('code="V3RACE"', $xml);
        self::assertStringContainsString('code="SDTCRACE"', $xml);
        self::assertStringContainsString('<raceCode xmlns="urn:hl7-org:sdtc"', $xml);
    }

    public function testCollidingCoreAndSdtcElementsSurviveAFullRoundTrip(): void
    {
        $service = $this->service();
        $xml     = $service->serializeToXml(new Patient(
            raceCode: new CE(code: 'V3RACE'),
            sdtcRaceCode: [new CE(code: 'SDTCRACE')],
        ));

        self::assertSame($xml, $service->serializeToXml($service->deserializeFromXml($xml, Patient::class)));
    }
}
