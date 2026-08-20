<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding as R5Coding;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive as R5UriPrimitive;
use Ardenexal\FHIRTools\Component\Validation\CodingSystemChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\TestCase;

/**
 * Message texts here are copied from the Java reference outcome in
 * vendor/fhir/fhir-test-cases/validator/outcomes/java/R4.mr-covid-bnd1-base.json, so a drift in our
 * wording fails the test rather than quietly turning a matching case into a differently worded one.
 */
final class CodingSystemCheckerTest extends TestCase
{
    public function testLocalReferenceIsReportedWithTheReferenceValidatorsWording(): void
    {
        self::assertSame(
            ['Coding.system must be an absolute reference, not a local reference'],
            $this->messagesFor(new Coding(system: new UriPrimitive(value: 'Location'))),
        );
    }

    public function testValueSetCanonicalIsReportedWithItsUrlQuoted(): void
    {
        self::assertSame(
            ["The Coding references a value set, not a code system ('http://hl7.org/fhir/ValueSet/measure-type')"],
            $this->messagesFor(new Coding(system: new UriPrimitive(value: 'http://hl7.org/fhir/ValueSet/measure-type'))),
        );
    }

    /**
     * The two rules are mutually exclusive: a value carrying a `/ValueSet/` path segment necessarily
     * also carries a scheme. One Coding therefore yields at most one finding, which is what keeps
     * `mr-covid-bnd1` at Java's 13/13 split rather than doubling it.
     */
    public function testOneCodingYieldsAtMostOneFinding(): void
    {
        self::assertCount(1, (new CodingSystemChecker())->check(
            new Coding(system: new UriPrimitive(value: 'http://hl7.org/fhir/ValueSet/measure-type')),
        ));
    }

    /**
     * `urn:` systems are absolute and correct, and they are common. A naive `://` test would report
     * every one of them.
     */
    public function testUrnSystemsAreAccepted(): void
    {
        self::assertSame(
            [],
            $this->messagesFor(
                new Coding(system: new UriPrimitive(value: 'urn:oid:2.16.840.1.113883.6.1')),
                new Coding(system: new UriPrimitive(value: 'urn:ietf:bcp:47')),
                new Coding(system: new UriPrimitive(value: 'urn:iso:std:iso:3166')),
                new Coding(system: new UriPrimitive(value: 'urn:iso-astm:E1762-95:2013')),
                new Coding(system: new UriPrimitive(value: 'http://snomed.info/sct')),
            ),
        );
    }

    /** Several corpus documents omit `Coding.system`; the reference validator reports nothing. */
    public function testAbsentSystemIsNotReported(): void
    {
        self::assertSame([], $this->messagesFor(new Coding(code: null), new Coding(system: new UriPrimitive())));
    }

    /**
     * The rule is scoped by the `#[FHIRComplexType(typeName: 'Coding')]` class attribute, never by
     * the property name. `Quantity.system` is also a `uri`, and `ContactPoint.system` legitimately
     * holds "phone"/"email" — a name-scoped rule would emit dozens of false errors across the corpus.
     */
    public function testSystemOnANonCodingElementIsIgnored(): void
    {
        self::assertSame([], (new CodingSystemChecker())->check(new Quantity(system: new UriPrimitive(value: 'Location'))));
    }

    /** The attribute covers every version's Coding class without version routing. */
    public function testR5CodingIsCoveredToo(): void
    {
        self::assertSame(
            ['Coding.system must be an absolute reference, not a local reference'],
            array_map(
                static fn (FHIRValidationViolation $v): string => $v->message,
                (new CodingSystemChecker())->check(new R5Coding(system: new R5UriPrimitive(value: 'Location'))),
            ),
        );
    }

    /** Findings are errors, the severity `FHIRValidationReport::errors()` counts. */
    public function testFindingsAreReportedAsErrorsAtTheSystemPath(): void
    {
        $violations = (new CodingSystemChecker())->check(
            new CodeableConcept(coding: [new Coding(system: new UriPrimitive(value: 'Location'))]),
        );

        self::assertCount(1, $violations);
        self::assertSame('error', $violations[0]->severity);
        self::assertSame('coding[0].system', $violations[0]->path);
    }

    /** @return list<string> */
    private function messagesFor(Coding ...$codings): array
    {
        return array_map(
            static fn (FHIRValidationViolation $v): string => $v->message,
            (new CodingSystemChecker())->check(new CodeableConcept(coding: $codings)),
        );
    }
}
