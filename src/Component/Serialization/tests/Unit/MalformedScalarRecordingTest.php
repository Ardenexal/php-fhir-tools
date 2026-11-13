<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\MalformedPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\MalformedPrimitiveRecorder;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * `boolean` and `integer` text the model cannot hold is recorded, and the coerced value is unchanged.
 *
 * Shapes are from `R5.primitive-bad`, `R5.group-choice-bad3` and `R4.Observation-ex-pain.xml`.
 *
 * @author Ardenexal
 */
final class MalformedScalarRecordingTest extends TestCase
{
    protected function setUp(): void
    {
        MalformedPrimitiveRecorder::reset();
    }

    protected function tearDown(): void
    {
        MalformedPrimitiveRecorder::reset();
    }

    public function testXmlBooleanTextOtherThanTrueOrFalseIsRecordedOnTheChoiceProperty(): void
    {
        $parameter = $this->xmlParameter('<valueBoolean value="TRUE"/>');

        self::assertEquals(
            [new MalformedPrimitive('value', MalformedPrimitive::KIND_BOOLEAN, 'TRUE')],
            MalformedPrimitiveRecorder::forObject($parameter),
        );
        // Recording changes nothing about the value the reader writes.
        self::assertFalse($parameter->value);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function malformedIntegers(): iterable
    {
        yield 'beyond 32 bits' => ['34534536346345345345'];
        yield 'not a number'   => ['$0'];
        yield 'exponent'       => ['1e4'];
        yield 'leading zero'   => ['007'];
        yield 'one past max'   => ['2147483648'];
    }

    #[DataProvider('malformedIntegers')]
    public function testXmlIntegerTextThatIsNotA32BitIntegerIsRecorded(string $lexeme): void
    {
        $parameter = $this->xmlParameter('<valueInteger value="' . $lexeme . '"/>');

        self::assertEquals(
            [new MalformedPrimitive('value', MalformedPrimitive::KIND_INTEGER, $lexeme)],
            MalformedPrimitiveRecorder::forObject($parameter),
        );
    }

    public function testWellFormedBooleansAndIntegersAreNotRecorded(): void
    {
        foreach (['<valueBoolean value="true"/>', '<valueBoolean value="false"/>', '<valueInteger value="0"/>', '<valueInteger value="-2147483648"/>', '<valueInteger value="+12"/>'] as $element) {
            self::assertSame([], MalformedPrimitiveRecorder::forObject($this->xmlParameter($element)), $element);
        }
    }

    /** `<valueInteger><extension …/></valueInteger>` is legal: the extension stands in for the value. */
    public function testExtensionOnlyElementIsNotRecorded(): void
    {
        $parameter = $this->xmlParameter(
            '<valueInteger><extension url="http://hl7.org/fhir/StructureDefinition/data-absent-reason"><valueCode value="unknown"/></extension></valueInteger>',
        );

        self::assertSame([], MalformedPrimitiveRecorder::forObject($parameter));
    }

    public function testXmlElementWithNeitherValueNorExtensionIsRecorded(): void
    {
        $observation = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize(
            '<Observation xmlns="http://hl7.org/fhir"><status value="final"/><code><text value="x"/></code>'
            . '<valueInteger><value value="0"/></valueInteger></Observation>',
        );

        self::assertIsObject($observation);
        self::assertEquals(
            [new MalformedPrimitive('value', MalformedPrimitive::KIND_NO_VALUE, null)],
            MalformedPrimitiveRecorder::forObject($observation),
        );
    }

    /**
     * A bare `<valueInteger/>` decodes to an empty string rather than an array, and is an element with
     * nothing in it; an explicit `value=""` is text, and judged as text.
     */
    public function testBareEmptyElementIsRecordedAsHavingNoValue(): void
    {
        self::assertEquals(
            [new MalformedPrimitive('value', MalformedPrimitive::KIND_NO_VALUE, null)],
            MalformedPrimitiveRecorder::forObject($this->xmlParameter('<valueInteger/>')),
        );
        self::assertEquals(
            [new MalformedPrimitive('value', MalformedPrimitive::KIND_INTEGER, '')],
            MalformedPrimitiveRecorder::forObject($this->xmlParameter('<valueInteger value=""/>')),
        );
    }

    /** A non-choice boolean property goes through a different branch of the reader. */
    public function testXmlBooleanOnAPlainPropertyIsRecorded(): void
    {
        $patient = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize(
            '<Patient xmlns="http://hl7.org/fhir"><active value="yes"/></Patient>',
        );

        self::assertIsObject($patient);
        self::assertEquals(
            [new MalformedPrimitive('active', MalformedPrimitive::KIND_BOOLEAN, 'yes')],
            MalformedPrimitiveRecorder::forObject($patient),
        );
    }

    /** JSON types its own booleans, so a number where one belongs is recorded as JSON text. */
    public function testJsonBooleanWrittenAsANumberIsRecorded(): void
    {
        $parameters = FHIRSerializationService::createDefault(FhirVersion::R5)->deserialize(
            '{"resourceType":"Parameters","parameter":[{"name":"b","valueBoolean":1},{"name":"c","valueBoolean":true}]}',
        );

        self::assertIsObject($parameters);
        self::assertEquals(
            [new MalformedPrimitive('value', MalformedPrimitive::KIND_BOOLEAN, '1')],
            MalformedPrimitiveRecorder::forObject($parameters->parameter[0]),
        );
        self::assertSame([], MalformedPrimitiveRecorder::forObject($parameters->parameter[1]));
    }

    private function xmlParameter(string $valueElement): object
    {
        $parameters = FHIRSerializationService::createDefault(FhirVersion::R5)->deserialize(
            '<Parameters xmlns="http://hl7.org/fhir"><parameter><name value="p"/>' . $valueElement . '</parameter></Parameters>',
        );

        self::assertIsObject($parameters);

        return $parameters->parameter[0];
    }
}
