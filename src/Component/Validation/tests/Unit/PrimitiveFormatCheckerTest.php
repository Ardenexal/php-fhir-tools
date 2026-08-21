<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive as R4Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Parameters\ParametersParameter as R4ParametersParameter;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ParametersResource as R4ParametersResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\OidPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
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


    // ---------------------------------------------------------------------------------------------
    // Shape rules. Every lexeme below is copied from a corpus fixture, and every expected message
    // from the matching reference outcome, so a drift in our wording fails here rather than quietly
    // turning a pairing case into an unpaired one.
    // ---------------------------------------------------------------------------------------------

    /**
     * The reference validator's base64 rule is a length test, and only a length test.
     *
     * `MEKH....SD/Z` is accepted on `R5.narrative-binary` despite its dots. An alphabet check looks
     * obviously correct, passes a hand-written test, and puts two corpus cases into ABOVE — so this
     * test exists to fail anyone who adds one back.
     */
    public function testBase64IsJudgedOnLengthNotAlphabet(): void
    {
        self::assertSame([], $this->messagesFor(new Base64BinaryPrimitive(value: 'MEKH....SD/Z')));
    }

    public function testBase64OfIllegalLengthIsReported(): void
    {
        self::assertSame(
            [
                "The value 'YXNhcs2Rhc2Q=' is not a valid Base64 value",
                "The value '%%%2@()()' is not a valid Base64 value",
            ],
            $this->messagesFor(
                new Base64BinaryPrimitive(value: 'YXNhcs2Rhc2Q='),
                new Base64BinaryPrimitive(value: '%%%2@()()'),
            ),
        );
    }

    /**
     * R5 makes base64 whitespace an error; R4 makes it a warning, and warning parity is out of scope.
     * Emitting an error on R4 would turn an agreeing case into a disagreeing one.
     */
    public function testBase64WhitespaceIsAnErrorOnR5AndSilentOnR4(): void
    {
        self::assertSame(
            ['Base64 encoded values are not allowed to contain any whitespace (per RFC 4648). '
                . 'Note that non-validating readers are encouraged to accept whitespace anyway'],
            $this->messagesFor(new Base64BinaryPrimitive(value: 'YXNh c2Rhc2Q=')),
        );

        $r4 = new R4ParametersResource(parameter: [
            new R4ParametersParameter(name: 'p', value: new R4Base64BinaryPrimitive(value: 'YXNh c2Rhc2Q=')),
        ]);

        self::assertSame([], array_map(
            static fn (FHIRValidationViolation $v): string => $v->message,
            (new PrimitiveFormatChecker())->check($r4),
        ));
    }

    /** Leading, trailing, doubled, and a non-breaking space — the four shapes the oracle flags. */
    public function testCodeWhitespaceIsReported(): void
    {
        self::assertSame(
            [
                "The code ' asdasd' is not valid (whitespace rules)",
                "The code 'asd  asd' is not valid (whitespace rules)",
                "The code 'asdasd ' is not valid (whitespace rules)",
                "The code 'CHEST\u{A0}' is not valid (whitespace rules)",
            ],
            $this->messagesFor(
                new CodePrimitive(value: ' asdasd'),
                new CodePrimitive(value: 'asd  asd'),
                new CodePrimitive(value: 'asdasd '),
                new CodePrimitive(value: "CHEST\u{A0}"),
            ),
        );
    }

    /** A single interior space is legal in a `code`, and most of the corpus relies on that. */
    public function testCodeWithOneInteriorSpaceIsAccepted(): void
    {
        self::assertSame([], $this->messagesFor(new CodePrimitive(value: 'asd asd')));
    }

    public function testIdShapeIsReported(): void
    {
        self::assertSame(
            [
                "id value ':12123-23' is not valid",
                "id value '12123/23' is not valid",
            ],
            $this->messagesFor(new IdPrimitive(value: ':12123-23'), new IdPrimitive(value: '12123/23')),
        );
    }

    public function testIdOverSixtyFourCharactersIsReported(): void
    {
        $long = str_repeat('a', 65);

        self::assertSame(["id value '{$long}' is not valid"], $this->messagesFor(new IdPrimitive(value: $long)));
    }

    /**
     * The four oid shapes from `R5.primitive-bad`, including the two that draw *two* findings each.
     * A value that fails the prefix is not also called an invalid OID.
     */
    public function testOidCombinationsMatchTheReferenceValidator(): void
    {
        self::assertSame(
            [
                'URI values cannot start with oid:',
                'OIDs must start with urn:oid:',
                "URI values cannot have whitespace('urn:oid: 0.1.2.3')",
                'OIDs must be valid ( 0.1.2.3)',
                'OIDs must be valid (a0.1.2.3)',
                'OIDs must start with urn:oid:',
            ],
            $this->messagesFor(
                new OidPrimitive(value: 'oid:0.1.2.3'),
                new OidPrimitive(value: 'urn:oid: 0.1.2.3'),
                new OidPrimitive(value: 'urn:oid:a0.1.2.3'),
                new OidPrimitive(value: '0.1.2.3'),
            ),
        );
    }

    public function testWellFormedOidIsAccepted(): void
    {
        self::assertSame([], $this->messagesFor(new OidPrimitive(value: 'urn:oid:2.16.840.1.113883.6.238')));
    }

    /** Note the reference validator's missing space before the parenthesis. */
    public function testUriWhitespaceIsReported(): void
    {
        self::assertSame(
            ["URI values cannot have whitespace('not a valid uri')"],
            $this->messagesFor(new UriPrimitive(value: 'not a valid uri')),
        );
    }

    public function testRelativeCanonicalIsReported(): void
    {
        self::assertSame(
            ['Canonical URLs must be absolute URLs if they are not fragment references (Library/library-cms146-example)'],
            $this->messagesFor(new CanonicalPrimitive(value: 'Library/library-cms146-example')),
        );
    }

    /** A fragment and a scheme-bearing URN are both legal canonicals. */
    public function testFragmentAndUrnCanonicalsAreAccepted(): void
    {
        self::assertSame(
            [],
            $this->messagesFor(
                new CanonicalPrimitive(value: '#contained-1'),
                new CanonicalPrimitive(value: 'urn:oid:2.16.840.1.113883.6.238'),
                new CanonicalPrimitive(value: 'http://example.org/fhir/StructureDefinition/x'),
            ),
        );
    }

    public function testPresentButEmptyPrimitiveIsReported(): void
    {
        self::assertSame(
            ['value cannot be empty', 'value cannot be empty'],
            $this->messagesFor(new StringPrimitive(value: ''), new MarkdownPrimitive(value: '')),
        );
    }

    /**
     * An empty value with an extension is extension-only presence, which the reference validator
     * says nothing about.
     */
    public function testEmptyPrimitiveCarryingAnExtensionIsNotReported(): void
    {
        self::assertSame(
            [],
            $this->messagesFor(new StringPrimitive(
                extension: [new Extension(url: 'http://hl7.org/fhir/StructureDefinition/data-absent-reason')],
                value: '',
            )),
        );
    }

    /**
     * `Meta.profile` deserializes to two entries from a one-element XML source, the second an empty
     * phantom. Until that reader defect is fixed, an empty primitive in a repeating position is our
     * bug and not the document's, so it must stay unreported.
     */
    public function testEmptyPrimitiveInARepeatingPositionIsNotReported(): void
    {
        $resource = new ParametersResource(meta: new Meta(profile: [new CanonicalPrimitive(value: '')]));

        self::assertSame([], array_map(
            static fn (FHIRValidationViolation $v): string => $v->message,
            (new PrimitiveFormatChecker())->check($resource),
        ));
    }

    /**
     * The reference validator has two decimal messages and they are not interchangeable: a lexeme
     * that is not a number, versus a number that breaks the regex's caps.
     */
    public function testTheTwoDecimalMessagesAreUsedForTheirOwnCases(): void
    {
        self::assertSame(
            ["The value '00.1' is not a valid decimal"],
            $this->messagesFor(new Quantity(value: '00.1')),
        );

        self::assertSame(
            ["Element value '1000000000000000000' does not meet decimal regex '" . PrimitiveFormatChecker::DECIMAL_SOURCE . "'"],
            $this->messagesFor(new Quantity(value: '1000000000000000000')),
        );
    }

    public function testTrailingDotDecimalIsNotANumber(): void
    {
        self::assertSame(
            ["The value '925.' is not a valid decimal"],
            $this->messagesFor(new Quantity(value: '925.')),
        );
    }

    /**
     * Both instants parse cleanly under brick/date-time, so parsing proving nothing is the point.
     * An out-of-range offset is worded as an unreadable instant; anything else fails the regex.
     */
    public function testParsedInstantsStillFailingTheCanonicalRegexAreReported(): void
    {
        self::assertSame(
            ["Element value '0000-01-01T12:32:45Z' does not meet instant regex '" . PrimitiveFormatChecker::INSTANT_SOURCE . "'"],
            $this->messagesFor(new InstantPrimitive(value: FHIRInstant::parse('0000-01-01T12:32:45Z'))),
        );

        self::assertSame(
            ["Not a valid instant format: '1983-01-01T12:32:45-15:00'"],
            $this->messagesFor(new InstantPrimitive(value: FHIRInstant::parse('1983-01-01T12:32:45-15:00'))),
        );
    }

    public function testDateTimeWithATimeButNoTimezoneIsReported(): void
    {
        self::assertSame(
            ['If a date has a time, it must have a timezone'],
            $this->messagesFor(new DateTimePrimitive(value: FHIRDateTime::parse('2020-11-11T10:58:14.768528'))),
        );
    }

    /** A date with no time needs no timezone, which is most of the corpus. */
    public function testDateWithoutATimeNeedsNoTimezone(): void
    {
        self::assertSame([], $this->messagesFor(new DateTimePrimitive(value: FHIRDateTime::parse('2020-11-11'))));
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
