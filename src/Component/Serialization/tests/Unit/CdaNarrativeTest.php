<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Component;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Section;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\StructuredBody;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\StructuredBodyComponent;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * A section's narrative must reach the wire as real child elements in urn:hl7-org:v3.
 *
 * The narrative is held on the model as a plain string: `hl7.cda.uv.core` publishes no StrucDoc
 * StructureDefinition, so there is no type to generate, and callers overwhelmingly already hold the
 * markup as text. Storing a string is a modelling choice; emitting one is a bug. It previously
 * emitted as `<text value="&lt;table&gt;…"/>` — escaped, into an attribute — because the value fell
 * through to the generic scalar path.
 *
 * Assertions are namespace-bound XPath and DOM node-type checks. A substring search for "<table>"
 * passes on escaped text, which is precisely the defect.
 */
final class CdaNarrativeTest extends TestCase
{
    private const string V3 = 'urn:hl7-org:v3';

    /**
     * Multiple top-level nodes with an element between two paragraphs: StrucDoc allows this where
     * FHIR narrative requires a single wrapping div, and the ordering is what a decoded-array
     * implementation would silently lose.
     */
    private const string NARRATIVE = '<paragraph>Current medications</paragraph>'
        . '<table><tbody><tr><td>Amoxicillin 500mg</td><td>TDS</td></tr></tbody></table>'
        . '<paragraph>Review in 7 days</paragraph>';

    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    private function documentWithNarrative(?string $narrative): ClinicalDocument
    {
        return new ClinicalDocument(
            id: new II(root: '1.2.3', extension: 'NARR-1'),
            component: new Component(
                structuredBody: new StructuredBody(
                    component: [new StructuredBodyComponent(section: new Section(
                        code: new CE(code: '101.16146', codeSystem: '1.2.36.1.2001.1001.101', displayName: 'Medications'),
                        text: $narrative,
                    ))],
                ),
            ),
        );
    }

    private function textElement(string $xml): \DOMElement
    {
        $dom = new \DOMDocument();
        self::assertTrue($dom->loadXML($xml), 'serialized output must be well-formed XML');

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('cda', self::V3);

        $nodes = $xpath->query(
            '/cda:ClinicalDocument/cda:component/cda:structuredBody/cda:component/cda:section/cda:text',
        );
        self::assertInstanceOf(\DOMNodeList::class, $nodes);

        $text = $nodes->item(0);
        self::assertInstanceOf(\DOMElement::class, $text, "no <text> element in: {$xml}");

        return $text;
    }

    public function testNarrativeTableIsAnElementNodeInTheDocumentNamespace(): void
    {
        $xml  = $this->service()->serializeToXml($this->documentWithNarrative(self::NARRATIVE));
        $text = $this->textElement($xml);

        $tables = [];
        foreach ($text->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->localName === 'table') {
                $tables[] = $child;
            }
        }

        self::assertCount(1, $tables, "the table must be an element node, not text: {$xml}");
        self::assertSame(self::V3, $tables[0]->namespaceURI, 'narrative content inherits the CDA namespace');

        // The whole subtree must be reachable, not just the outermost element.
        $xpath = new \DOMXPath($text->ownerDocument);
        $xpath->registerNamespace('cda', self::V3);
        self::assertSame(2, $xpath->query('cda:table/cda:tbody/cda:tr/cda:td', $text)->length);
    }

    public function testNarrativeIsNotEscapedIntoAnAttribute(): void
    {
        $xml  = $this->service()->serializeToXml($this->documentWithNarrative(self::NARRATIVE));
        $text = $this->textElement($xml);

        self::assertFalse($text->hasAttribute('value'), "<text> must carry no value attribute: {$xml}");
        self::assertStringNotContainsString('&lt;table', $xml, 'markup must not be escaped');
    }

    public function testNarrativeChildOrderIsPreserved(): void
    {
        $xml  = $this->service()->serializeToXml($this->documentWithNarrative(self::NARRATIVE));
        $text = $this->textElement($xml);

        $order = [];
        foreach ($text->childNodes as $child) {
            if ($child instanceof \DOMElement) {
                $order[] = $child->localName;
            }
        }

        self::assertSame(['paragraph', 'table', 'paragraph'], $order, 'document order must survive');
    }

    public function testNarrativeSurvivesARoundTrip(): void
    {
        $service = $this->service();

        $xml  = $service->serializeToXml($this->documentWithNarrative(self::NARRATIVE));
        $back = $service->deserializeFromXml($xml, ClinicalDocument::class);

        $section = $back->component?->structuredBody?->component[0]->section ?? null;
        self::assertInstanceOf(Section::class, $section);
        self::assertSame(self::NARRATIVE, $section->text);
    }

    public function testPlainTextNarrativeEmitsAsTextContent(): void
    {
        $xml  = $this->service()->serializeToXml($this->documentWithNarrative('No known medications'));
        $text = $this->textElement($xml);

        self::assertSame('No known medications', $text->textContent);
        self::assertFalse($text->hasAttribute('value'));
    }

    /**
     * A narrative is author-supplied, so unparsable markup must degrade to text rather than take the
     * whole document down with it.
     */
    public function testUnparsableNarrativeDegradesToTextInsteadOfBreakingTheDocument(): void
    {
        $xml  = $this->service()->serializeToXml($this->documentWithNarrative('<paragraph>unclosed'));
        $text = $this->textElement($xml);

        self::assertSame('<paragraph>unclosed', $text->textContent);
    }
}
