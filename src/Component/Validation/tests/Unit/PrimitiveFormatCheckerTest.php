<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Parameters\ParametersParameter;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ParametersResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\PrimitiveFormatChecker;
use PHPUnit\Framework\TestCase;

/**
 * Message texts here are copied from the Java reference outcomes in
 * vendor/fhir/fhir-test-cases/validator/outcomes/java/R5.primitive-{bad,good}-base.json, so a drift
 * in our wording fails the test rather than quietly turning a matching case into a differently
 * worded one.
 */
final class PrimitiveFormatCheckerTest extends TestCase
{
    public function testUnparseableInstantIsReportedWithItsOriginalLexeme(): void
    {
        self::assertSame(
            ["Not a valid instant format: '1983-00-01T12:32:45Z'"],
            $this->messagesFor(new InstantPrimitive(value: FHIRInstant::unparsed('1983-00-01T12:32:45Z', 'Invalid month-of-year: 0'))),
        );
    }

    public function testDateAndDateTimeUseTheReferenceValidatorsWording(): void
    {
        self::assertSame(
            [
                "Not a valid date format: '1900-02-29'",
                "Not a valid date/time format: '-0001-01-01'",
            ],
            $this->messagesFor(
                new DatePrimitive(value: FHIRDate::unparsed('1900-02-29', 'not a leap year')),
                new DateTimePrimitive(value: FHIRDateTime::unparsed('-0001-01-01', 'unparsable')),
            ),
        );
    }

    /**
     * FHIR's seconds field is `([0-5][0-9]|60)`, so a leap second is a legal lexeme the reference
     * validator accepts and reports nothing about. Only brick/date-time cannot represent it, and
     * blaming the document for our own limitation would turn `primitive-good` — a document the
     * oracle passes on every temporal — into a disagreement.
     */
    public function testLeapSecondIsNotReported(): void
    {
        self::assertSame(
            [],
            $this->messagesFor(new DateTimePrimitive(value: FHIRDateTime::unparsed('2013-01-01T12:59:60+10:00', 'unsupported'))),
        );
    }

    /** Minute 60 is genuinely invalid, and the leap-second exemption must not swallow it. */
    public function testMinuteSixtyIsStillReported(): void
    {
        self::assertSame(
            ["Not a valid date/time format: '2013-01-01T12:60:59+10:00'"],
            $this->messagesFor(new DateTimePrimitive(value: FHIRDateTime::unparsed('2013-01-01T12:60:59+10:00', 'invalid minute'))),
        );
    }

    public function testParsedTemporalIsNotReported(): void
    {
        self::assertSame([], $this->messagesFor(new DateTimePrimitive(value: FHIRDateTime::parse('2013-01-01T12:32:45+10:00'))));
    }

    /**
     * `<valueDate><extension url="…data-absent-reason"/></valueDate>` builds a wrapper whose value
     * is absent. The reference validator reports nothing about it.
     */
    public function testAbsentValueIsNotReported(): void
    {
        self::assertSame([], $this->messagesFor(new DatePrimitive()));
    }

    public function testDecimalOutsideTheCanonicalRegexIsReported(): void
    {
        self::assertSame(
            ["Element value '1e09' does not meet decimal regex '-?(0|[1-9][0-9]{0,17})(\\.[0-9]{1,17})?([eE](0|[+\\-]?[1-9][0-9]{0,9}))?'"],
            $this->messagesFor(new Quantity(value: '1e09')),
        );
    }

    /**
     * Exponents the spec allows. The generated `DecimalPrimitive` Regex constraint rejects every one
     * of these — it emits a stray closing brace — which is why this rule carries its own pattern.
     */
    public function testLegalDecimalsAreAccepted(): void
    {
        self::assertSame(
            [],
            $this->messagesFor(
                new Quantity(value: '1e1'),
                new Quantity(value: '1.0e-1'),
                new Quantity(value: '0.1e11'),
                new Quantity(value: '0.12e3'),
                new Quantity(value: '-0.5'),
            ),
        );
    }

    /**
     * The empty string is how an extension-only choice scalar records that its element was present;
     * that is absence, not a malformed number.
     */
    public function testEmptyDecimalIsNotReported(): void
    {
        self::assertSame([], $this->messagesFor(new Quantity(value: '')));
    }

    /** @return list<string> */
    private function messagesFor(object ...$values): array
    {
        $resource = new ParametersResource(
            parameter: array_map(
                static fn (object $value): ParametersParameter => new ParametersParameter(name: 'p', value: $value),
                $values,
            ),
        );

        return array_map(
            static fn (FHIRValidationViolation $v): string => $v->message,
            (new PrimitiveFormatChecker())->check($resource),
        );
    }
}
