<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuObservation;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Observation;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\SubstanceAdministration;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLPQ;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\PQ;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ST;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\ActClassObservation;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * A CDA element admitting several datatypes must name the one it holds.
 *
 * Every assertion here reads the ATTRIBUTE. An assertion on the element or its text passed before this
 * behaviour existed, which is why the defect shipped: the document was well-formed, the value and its
 * code were correct, and only a schema-validating receiver rejected it.
 */
final class CdaPolymorphicValueTypeTest extends TestCase
{
    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    private function observationWith(object $value): string
    {
        return $this->service()->serializeToXml(new AuObservation(
            classCode: ActClassObservation::obs,
            moodCode:  'EVN',
            code:      new CD(code: '102.16134', codeSystem: '1.2.36.1.2001.1001.101'),
            value:     [$value],
        ));
    }

    public function testATextValueNamesItsDatatype(): void
    {
        $xml = $this->observationWith(new ST(xmlText: 'No Recommendations.'));

        self::assertStringContainsString('xsi:type="ST"', $xml);
    }

    public function testACodedValueNamesItsDatatype(): void
    {
        $xml = $this->observationWith(new CD(code: '01', codeSystem: '1.2.36.1.2001.1001.101.104.16299'));

        self::assertStringContainsString('xsi:type="CD"', $xml);
    }

    public function testTheReportedTextValueMatchesTheExpectedElementExactly(): void
    {
        $xml = $this->observationWith(new ST(xmlText: 'No Recommendations.'));

        self::assertStringContainsString(
            '<value xsi:type="ST" representation="TXT" mediaType="text/plain">No Recommendations.</value>',
            $xml,
        );
    }

    public function testAnIntervalValueUsesThePublishedNameNotThePhpClassName(): void
    {
        // The PHP class is IVLPQ and the canonical URL ends IVL-PQ; CDA publishes IVL_PQ. Deriving the
        // name from either would emit a plausible, wrong discriminator.
        $xml = $this->observationWith(new IVLPQ());

        self::assertStringContainsString('xsi:type="IVL_PQ"', $xml);
        self::assertStringNotContainsString('xsi:type="IVLPQ"', $xml);
        self::assertStringNotContainsString('xsi:type="IVL-PQ"', $xml);
    }

    public function testASubtypeIsNamedAsItselfRatherThanAsItsParent(): void
    {
        // CE derives from CD, and the definitions list CD first. Walking that order would name a CE
        // value "CD" — structurally valid, and wrong.
        $xml = $this->observationWith(new CE(code: 'x', codeSystem: '1.2.3'));

        self::assertStringContainsString('xsi:type="CE"', $xml);
        self::assertStringNotContainsString('xsi:type="CD"', $xml);
    }

    public function testTheSchemaInstanceNamespaceIsDeclaredOnTheDocumentRoot(): void
    {
        $xml = $this->observationWith(new ST(xmlText: 'x'));

        self::assertStringContainsString('xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"', $xml);
        self::assertSame(
            1,
            substr_count($xml, 'xmlns:xsi='),
            'declared once, on the root, never re-declared on the element that uses it',
        );
    }

    public function testAnElementWithOneFixedDatatypeIsNotGivenADatatypeName(): void
    {
        // An observation's code is a CD and only a CD, so the schema does not admit the attribute
        // there. Stamping it would change output receivers already accept.
        $xml = $this->observationWith(new ST(xmlText: 'x'));

        self::assertStringContainsString('<code code="102.16134"', $xml);
        self::assertStringNotContainsString('<code xsi:type', $xml);
        self::assertSame(1, substr_count($xml, 'xsi:type='), 'only the value element is polymorphic');
    }

    public function testAnEffectiveTimeNamesItsDatatypeToo(): void
    {
        // A second polymorphic slot, whose datatypes bottom out at SXCM_TS rather than ANY.
        $xml = $this->service()->serializeToXml(new SubstanceAdministration(
            moodCode:      'EVN',
            effectiveTime: [new IVLTS()],
        ));

        self::assertStringContainsString('xsi:type="IVL_TS"', $xml);
    }

    public function testEachOccurrenceOfARepeatingElementNamesItsOwnDatatype(): void
    {
        $xml = $this->service()->serializeToXml(new Observation(
            value: [new ST(xmlText: 'text'), new PQ(value: 5.0), new CD(code: 'c', codeSystem: '1.2.3')],
        ));

        self::assertStringContainsString('xsi:type="ST"', $xml);
        self::assertStringContainsString('xsi:type="PQ"', $xml);
        self::assertStringContainsString('xsi:type="CD"', $xml);
        self::assertSame(3, substr_count($xml, 'xsi:type='));
        // Each occurrence stays its own <value> element rather than collapsing into a wrapper.
        self::assertSame(3, substr_count($xml, '<value '));
        self::assertStringNotContainsString('<item', $xml);
    }

    public function testASingleOccurrenceKeepsItsElementNameAndAttributes(): void
    {
        // The attribute goes on the element, not on a wrapper around it. Stamping the wrapper mixes a
        // string key into a list and the encoder then emits <item key="0">, losing the element name.
        $xml = $this->observationWith(new ST(xmlText: 'x'));

        self::assertStringNotContainsString('<item', $xml);
        self::assertStringContainsString('representation="TXT"', $xml);
    }

    public function testANonCdaDocumentIsUnaffected(): void
    {
        // The schema-instance namespace belongs to CDA. A datatype serialized as its own root still
        // gets it, but nothing about a non-polymorphic CDA type gains a type attribute.
        $xml = $this->service()->serializeToXml(new II(root: '2.16.840.1.113883.19.5'));

        self::assertStringNotContainsString('xsi:type', $xml);
    }
}
