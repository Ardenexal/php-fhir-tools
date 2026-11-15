<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Criterion;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Observation;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ObservationRange;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLPQ;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\PQ;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ST;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Reading a typed CDA element back must select the datatype the document declared.
 *
 * The declared type of a polymorphic element is abstract, so without this the round trip does not merely
 * pick the wrong class — it cannot construct anything at all.
 */
final class CdaPolymorphicValueRoundTripTest extends TestCase
{
    private const string V3  = 'urn:hl7-org:v3';

    private const string XSI = 'http://www.w3.org/2001/XMLSchema-instance';

    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    private function observationXml(string $valueElement, string $xsiPrefix = 'xsi'): string
    {
        return '<Observation xmlns="' . self::V3 . '" xmlns:' . $xsiPrefix . '="' . self::XSI . '"'
            . ' moodCode="EVN">' . $valueElement . '</Observation>';
    }

    public function testATextValueReadsBackAsText(): void
    {
        $observation = $this->service()->deserializeFromXml(
            $this->observationXml('<value xsi:type="ST" representation="TXT">No Recommendations.</value>'),
            Observation::class,
        );

        self::assertInstanceOf(ST::class, $observation->value[0]);
        self::assertSame('No Recommendations.', $observation->value[0]->xmlText);
    }

    public function testACodedValueReadsBackAsACode(): void
    {
        $observation = $this->service()->deserializeFromXml(
            $this->observationXml('<value xsi:type="CD" code="01" codeSystem="1.2.3"/>'),
            Observation::class,
        );

        self::assertInstanceOf(CD::class, $observation->value[0]);
        self::assertSame('01', $observation->value[0]->code);
    }

    public function testAnUnderscoredIntervalNameReadsBackAsItsClass(): void
    {
        $observation = $this->service()->deserializeFromXml(
            $this->observationXml('<value xsi:type="IVL_PQ"/>'),
            Observation::class,
        );

        self::assertInstanceOf(IVLPQ::class, $observation->value[0]);
    }

    public function testASubtypeReadsBackAsTheSubtypeNotItsParent(): void
    {
        $observation = $this->service()->deserializeFromXml(
            $this->observationXml('<value xsi:type="CE" code="x" codeSystem="1.2.3"/>'),
            Observation::class,
        );

        self::assertInstanceOf(CE::class, $observation->value[0]);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function writtenValueProvider(): iterable
    {
        yield 'text'     => ['ST'];
        yield 'code'     => ['CD'];
        yield 'quantity' => ['PQ'];
        yield 'interval' => ['IVL_PQ'];
    }

    #[DataProvider('writtenValueProvider')]
    public function testWhatWeWriteWeCanReadBack(string $typeName): void
    {
        $value = match ($typeName) {
            'ST'     => new ST(xmlText: 'text'),
            'CD'     => new CD(code: 'c', codeSystem: '1.2.3'),
            'PQ'     => new PQ(value: 5.0),
            'IVL_PQ' => new IVLPQ(),
        };

        $service = $this->service();
        $xml     = $service->serializeToXml(new Observation(moodCode: 'EVN', value: [$value]));

        self::assertStringContainsString('xsi:type="' . $typeName . '"', $xml);
        self::assertInstanceOf(
            $value::class,
            $service->deserializeFromXml($xml, Observation::class)->value[0],
        );
    }

    public function testTheDatatypeIsReadByNamespaceNotByPrefix(): void
    {
        // `xsi` is only a convention. A document may bind any prefix to the schema-instance namespace,
        // and the decoder keys on the raw prefixed name, so matching the literal string "@xsi:type"
        // would silently miss this conformant document.
        $observation = $this->service()->deserializeFromXml(
            $this->observationXml('<value xs:type="ST" representation="TXT">hi</value>', 'xs'),
            Observation::class,
        );

        self::assertInstanceOf(ST::class, $observation->value[0]);
    }

    public function testTwoPrefixesForTheSameNamespaceReadIdentically(): void
    {
        $service = $this->service();

        $conventional = $service->deserializeFromXml(
            $this->observationXml('<value xsi:type="CD" code="01" codeSystem="1.2.3"/>'),
            Observation::class,
        );
        $unusual = $service->deserializeFromXml(
            $this->observationXml('<value x:type="CD" code="01" codeSystem="1.2.3"/>', 'x'),
            Observation::class,
        );

        self::assertEquals($conventional, $unusual);
    }

    public function testATypeAttributeInAnUnrelatedNamespaceIsNotAccepted(): void
    {
        // Prefix matching would take this; namespace matching refuses it. Accepting it would let a
        // foreign attribute steer which datatype the document is read as.
        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/must carry an xsi:type attribute/');

        $this->service()->deserializeFromXml(
            '<Observation xmlns="' . self::V3 . '" xmlns:bogus="urn:not-xsi" moodCode="EVN">'
            . '<value bogus:type="ST" representation="TXT">hi</value></Observation>',
            Observation::class,
        );
    }

    public function testAPolymorphicElementWithoutADatatypeIsReportedNotGuessed(): void
    {
        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/must carry an xsi:type attribute naming the one it holds/');

        $this->service()->deserializeFromXml(
            '<Observation xmlns="' . self::V3 . '" moodCode="EVN"><value code="01"/></Observation>',
            Observation::class,
        );
    }

    public function testAnUnpermittedDatatypeNamesWhatIsAllowed(): void
    {
        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/is not a datatype it may hold\. Permitted: /');

        $this->service()->deserializeFromXml(
            $this->observationXml('<value xsi:type="NotADatatype"/>'),
            Observation::class,
        );
    }

    public function testEachOccurrenceOfARepeatingElementReadsBackAsItsOwnDatatype(): void
    {
        $service = $this->service();
        $xml     = $service->serializeToXml(new Observation(
            moodCode: 'EVN',
            value:    [new ST(xmlText: 'text'), new PQ(value: 2.0), new CD(code: 'c', codeSystem: '1.2.3')],
        ));

        $back = $service->deserializeFromXml($xml, Observation::class);

        self::assertCount(3, $back->value);
        self::assertInstanceOf(ST::class, $back->value[0]);
        self::assertInstanceOf(PQ::class, $back->value[1]);
        self::assertInstanceOf(CD::class, $back->value[2]);
    }

    public function testASingleValuedPolymorphicElementReadsBackAsOneObjectNotAList(): void
    {
        // An observation range's value holds one datatype, not a list. The metadata that names an
        // array's item type must not be set on it, or the reader builds a list it cannot assign.
        //
        // `unit` rather than `value` is asserted deliberately. A CDA attribute literally named `value`
        // does not survive deserialization on any datatype that has one, because the normalizer treats
        // `@value` as a FHIR primitive's content throughout. That is a separate, pre-existing defect —
        // a plain `PQ` loses it as its own document root too — and asserting it here would tie this
        // test to a bug it does not cover.
        $service = $this->service();
        $xml     = $service->serializeToXml(new ObservationRange(value: new PQ(value: 5.0, unit: 'mg')));

        $back = $service->deserializeFromXml($xml, ObservationRange::class);

        self::assertInstanceOf(PQ::class, $back->value);
        self::assertSame('mg', $back->value->unit);
    }

    public function testTheOtherSingleValuedPolymorphicElementRoundTripsToo(): void
    {
        $service = $this->service();
        $xml     = $service->serializeToXml(new Criterion(value: new CD(code: 'c', codeSystem: '1.2.3')));

        self::assertInstanceOf(CD::class, $service->deserializeFromXml($xml, Criterion::class)->value);
    }

    public function testAnElementWithOneFixedDatatypeStillReadsBackWithoutADatatypeAttribute(): void
    {
        // An observation's code is a CD and only a CD. It carries no xsi:type, and requiring one there
        // would break every document that omits it correctly.
        $observation = $this->service()->deserializeFromXml(
            $this->observationXml(
                '<code code="102.16134" codeSystem="1.2.3"/>'
                . '<value xsi:type="ST" representation="TXT">hi</value>',
            ),
            Observation::class,
        );

        self::assertInstanceOf(CD::class, $observation->code);
        self::assertSame('102.16134', $observation->code->code);
    }
}
