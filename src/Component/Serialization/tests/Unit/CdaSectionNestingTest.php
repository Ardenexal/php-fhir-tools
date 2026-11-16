<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Component;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Section;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\SectionComponent;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\StructuredBody;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\StructuredBodyComponent;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ST;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * A structured body must nest as <component><section>…</section></component>.
 *
 * `hl7.cda.uv.core` does not publish a named type for the section-level component wrapper: it
 * declares the wrapper inline, as nested paths under `StructuredBody.component`
 * (`.typeCode`, `.contextConductionInd`, `.section`). The generator skips element paths deeper
 * than one dot, so those children were dropped and `StructuredBody::$component` typed at the
 * generic `InfrastructureRoot` base — placing a `Section` there emitted the section's own fields
 * directly inside `<component>`, with no `<section>` element at all.
 *
 * Every assertion here is namespace-bound XPath against `urn:hl7-org:v3`. Substring matching
 * cannot tell `<component><section><code/></section></component>` from
 * `<component><code/></component>`, which is exactly the defect.
 */
final class CdaSectionNestingTest extends TestCase
{
    private const string V3 = 'urn:hl7-org:v3';

    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    /**
     * @return list<\DOMElement>
     */
    private function query(string $xml, string $expression): array
    {
        $dom = new \DOMDocument();
        self::assertTrue($dom->loadXML($xml), 'serialized output must be well-formed XML');

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('cda', self::V3);

        $nodes = $xpath->query($expression);
        self::assertInstanceOf(\DOMNodeList::class, $nodes, "invalid XPath: {$expression}");

        $elements = [];
        foreach ($nodes as $node) {
            if ($node instanceof \DOMElement) {
                $elements[] = $node;
            }
        }

        return $elements;
    }

    private function documentWithOneSection(): ClinicalDocument
    {
        $section = new Section(
            code: new CE(code: '101.16146', codeSystem: '1.2.36.1.2001.1001.101', displayName: 'Medications'),
            title: new ST(xmlText: 'Medications'),
        );

        return new ClinicalDocument(
            id: new II(root: '1.2.3', extension: 'NARR-1'),
            component: new Component(
                structuredBody: new StructuredBody(
                    component: [new StructuredBodyComponent(section: $section)],
                ),
            ),
        );
    }

    public function testSectionNestsInsideItsComponentWrapper(): void
    {
        $xml = $this->service()->serializeToXml($this->documentWithOneSection());

        $sections = $this->query(
            $xml,
            '/cda:ClinicalDocument/cda:component/cda:structuredBody/cda:component/cda:section',
        );

        self::assertCount(1, $sections, "expected one nested <section>, got: {$xml}");
    }

    public function testSectionCodeStaysInsideTheSectionAndIsNotHoisted(): void
    {
        $xml = $this->service()->serializeToXml($this->documentWithOneSection());

        $inSection = $this->query(
            $xml,
            '/cda:ClinicalDocument/cda:component/cda:structuredBody/cda:component/cda:section/cda:code',
        );
        self::assertCount(1, $inSection, "section code must be inside <section>, got: {$xml}");
        self::assertSame('101.16146', $inSection[0]->getAttribute('code'));

        $hoisted = $this->query(
            $xml,
            '/cda:ClinicalDocument/cda:component/cda:structuredBody/cda:component/cda:code',
        );
        self::assertCount(0, $hoisted, "section code must not be hoisted into <component>, got: {$xml}");
    }

    public function testDocumentRootIsUnchanged(): void
    {
        $xml = $this->service()->serializeToXml($this->documentWithOneSection());

        $dom = new \DOMDocument();
        self::assertTrue($dom->loadXML($xml));

        $root = $dom->documentElement;
        self::assertInstanceOf(\DOMElement::class, $root);
        self::assertSame('ClinicalDocument', $root->localName);
        self::assertSame(self::V3, $root->namespaceURI);
    }

    /**
     * A subsection nests through a second wrapper, `Section.component`, which CDA declares inline
     * exactly like the body-level one. Covered here because the two wrappers are generated from the
     * same rule: if only the body-level one were special-cased, this is what would break.
     */
    public function testSubsectionNestsThroughItsOwnWrapper(): void
    {
        $subsection = new Section(
            code: new CE(code: '101.16020', codeSystem: '1.2.36.1.2001.1001.101', displayName: 'Current Medications'),
        );
        $parent = new Section(
            code: new CE(code: '101.16146', codeSystem: '1.2.36.1.2001.1001.101', displayName: 'Medications'),
            component: [new SectionComponent(section: $subsection)],
        );

        $document = new ClinicalDocument(
            id: new II(root: '1.2.3', extension: 'NARR-2'),
            component: new Component(
                structuredBody: new StructuredBody(
                    component: [new StructuredBodyComponent(section: $parent)],
                ),
            ),
        );

        $xml = $this->service()->serializeToXml($document);

        $nested = $this->query(
            $xml,
            '/cda:ClinicalDocument/cda:component/cda:structuredBody/cda:component/cda:section'
            . '/cda:component/cda:section/cda:code',
        );

        self::assertCount(1, $nested, "expected a subsection nested through <component>, got: {$xml}");
        self::assertSame('101.16020', $nested[0]->getAttribute('code'));
    }
}
