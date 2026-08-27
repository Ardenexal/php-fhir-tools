<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuControlAct;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuEntry;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Component;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\NonXMLBody;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\BinaryDataEncoding;
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
     * An attachment-only document: the simplest CDA that populates an enum-typed property.
     *
     * 227 of the 260 generated CDA classes declare at least one enum-typed property, so the enum
     * path is reached by practically every real document. It is easy to miss because a document
     * that sets no enum serializes correctly, which is why this case builds a whole
     * ClinicalDocument and asserts the document header alongside the enum attribute: the header
     * behaviour must survive the enum handling.
     */
    public function testEnumTypedAttributeRoundTripsWithoutDisturbingTheDocumentHeader(): void
    {
        $service = $this->service();

        $document = new ClinicalDocument(
            id: new II(root: '1.2.3', extension: 'ENUM-1'),
            component: new Component(
                nonXMLBody: new NonXMLBody(
                    text: new ED(
                        mediaType: 'application/pdf',
                        representation: BinaryDataEncoding::base64_encodedtext,
                        xmlText: 'JVBERi0x',
                    ),
                ),
            ),
        );

        $xml = $service->serializeToXml($document);

        // The backing code, not the PHP case name (base64_encodedtext) and not an object dump.
        self::assertStringContainsString('representation="B64"', $xml);
        self::assertStringNotContainsString('base64_encodedtext', $xml);

        // The header that already worked before enum support must not regress.
        $dom = new \DOMDocument();
        self::assertTrue($dom->loadXML($xml));
        self::assertSame('ClinicalDocument', $dom->documentElement?->localName);
        self::assertSame('urn:hl7-org:v3', $dom->documentElement?->namespaceURI);

        $decoded = $service->deserializeFromXml($xml, ClinicalDocument::class);

        self::assertInstanceOf(ClinicalDocument::class, $decoded);
        self::assertSame(
            BinaryDataEncoding::base64_encodedtext,
            $decoded->component?->nonXMLBody?->text?->representation,
        );
    }
}
