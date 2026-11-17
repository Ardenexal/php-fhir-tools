<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * A FHIR `Narrative.div` read from XML must keep its text and its document order.
 *
 * XmlEncoder's decode groups same-named siblings and splits an element's text away from its child
 * elements, so a narrative rebuilt from the decoded array lost the text of anchors and reordered
 * `<p>`/`<table>` blocks (`R4.message` in fhir-test-cases). The div is now read from the source DOM,
 * the same way CDA narrative already was.
 */
final class FhirNarrativeXmlTest extends TestCase
{
    public function testMixedContentAndSiblingOrderSurvive(): void
    {
        $div = $this->divOf(
            '<div xmlns="http://www.w3.org/1999/xhtml"><p><b>sender</b>: <a href="urn:uuid:1">Saint Luke</a> tail</p>'
            . '<table><tr><td>x</td></tr></table><p>after</p></div>',
        );

        self::assertSame(
            '<div><p><b>sender</b>: <a href="urn:uuid:1">Saint Luke</a> tail</p><table><tr><td>x</td></tr></table><p>after</p></div>',
            $div,
        );
    }

    public function testDivAttributesAreKept(): void
    {
        self::assertSame(
            '<div lang="en" id="n1"><p>x</p></div>',
            $this->divOf('<div xmlns="http://www.w3.org/1999/xhtml" lang="en" id="n1"><p>x</p></div>'),
        );
    }

    /**
     * A div outside the XHTML namespace keeps its namespace, so validation can report it
     * (`R5.list-xhtml-wrongns1` and `wrongns3`). An element in the XHTML namespace is written bare,
     * as before.
     */
    public function testWrongNamespaceIsKeptOnTheElementThatCarriesIt(): void
    {
        self::assertSame(
            '<div xmlns="http://www.w3.org/1999/xhtmlx"><p>x</p></div>',
            $this->divOf('<div xmlns="http://www.w3.org/1999/xhtmlx"><p>x</p></div>'),
        );
        self::assertSame(
            '<div xmlns="http://hl7.org/fhir"><p>x</p></div>',
            $this->divOf('<div><p>x</p></div>'),
        );
        self::assertSame(
            '<div><p xmlns="http://www.w3.org/1999/xhtmlx">x</p></div>',
            $this->divOf('<div xmlns="http://www.w3.org/1999/xhtml"><n:p xmlns:n="http://www.w3.org/1999/xhtmlx">x</n:p></div>'),
        );
    }

    /** An XHTML child of a wrong-namespace div is still XHTML: only the div is wrong. */
    public function testXhtmlChildOfAWrongNamespaceDivKeepsTheXhtmlNamespace(): void
    {
        self::assertSame(
            '<div xmlns="http://hl7.org/fhir"><p xmlns="http://www.w3.org/1999/xhtml">x</p></div>',
            $this->divOf('<div><p xmlns="http://www.w3.org/1999/xhtml">x</p></div>'),
        );
    }

    /** Writing always emits the namespace FHIR requires, whatever the model holds. */
    public function testWrongNamespaceIsCorrectedOnWrite(): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);
        $patient = $service->deserializeFromXml($this->patientXml('<div><p>x</p></div>'), PatientResource::class);

        self::assertStringContainsString(
            '<div xmlns="http://www.w3.org/1999/xhtml"><p>x</p></div>',
            $service->serializeToXml($patient),
        );
    }

    public function testNarrativeInsideABundleEntryIsReadInOrder(): void
    {
        $xml = '<Bundle xmlns="http://hl7.org/fhir"><type value="collection"/><entry><resource><Patient><text>'
            . '<status value="generated"/><div xmlns="http://www.w3.org/1999/xhtml"><p>a: <a href="#x">b</a></p><p>c</p></div>'
            . '</text></Patient></resource></entry></Bundle>';

        $bundle = FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromXml($xml, BundleResource::class);
        self::assertInstanceOf(BundleResource::class, $bundle);

        $patient = $bundle->entry[0]->resource ?? null;
        self::assertInstanceOf(PatientResource::class, $patient);
        self::assertSame('<div><p>a: <a href="#x">b</a></p><p>c</p></div>', $patient->text?->div?->value);
    }

    public function testXmlRoundTripStillDeclaresTheXhtmlNamespace(): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);
        $patient = $service->deserializeFromXml($this->patientXml('<div xmlns="http://www.w3.org/1999/xhtml"><p>a <b>b</b> c</p></div>'), PatientResource::class);

        self::assertStringContainsString(
            '<div xmlns="http://www.w3.org/1999/xhtml"><p>a <b>b</b> c</p></div>',
            $service->serializeToXml($patient),
        );
    }

    public function testEmptyAndTextOnlyDivsRoundTrip(): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);

        foreach (['<div xmlns="http://www.w3.org/1999/xhtml"/>', '<div xmlns="http://www.w3.org/1999/xhtml">just text</div>'] as $div) {
            $patient = $service->deserializeFromXml($this->patientXml($div), PatientResource::class);
            self::assertStringContainsString($div, $service->serializeToXml($patient));
        }
    }

    private function divOf(string $div): ?string
    {
        $patient = FHIRSerializationService::createDefault(FhirVersion::R4)->deserializeFromXml($this->patientXml($div), PatientResource::class);
        self::assertInstanceOf(PatientResource::class, $patient);

        return $patient->text?->div?->value;
    }

    private function patientXml(string $div): string
    {
        return '<Patient xmlns="http://hl7.org/fhir"><id value="p"/><text><status value="generated"/>' . $div . '</text></Patient>';
    }
}
