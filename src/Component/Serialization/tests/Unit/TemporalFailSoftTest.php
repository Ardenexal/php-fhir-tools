<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTemporalValue;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ParametersResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * A temporal lexeme that will not parse must not cost the reader the rest of the document.
 *
 * Malformed primitive syntax is a FHIR *validation* finding. The HL7 Java reference validator reads
 * `primitive-bad.xml` end to end and reports forty located errors; we aborted on the first bad
 * temporal and so reported none of them — the document never reached the validator at all.
 */
final class TemporalFailSoftTest extends TestCase
{
    private const BAD = <<<'XML'
        <Parameters xmlns="http://hl7.org/fhir">
          <parameter>
            <name value="paramDate"/>
            <valueDate value="1900-02-29"/>
          </parameter>
          <parameter>
            <name value="paramString"/>
            <valueString value="still readable"/>
          </parameter>
        </Parameters>
        XML;

    public function testDocumentSurvivesAnUnparseableTemporalAndKeepsTheLexeme(): void
    {
        $resource = FHIRSerializationService::createDefault(FhirVersion::R5)->deserialize(self::BAD);

        self::assertInstanceOf(ParametersResource::class, $resource);
        self::assertCount(2, $resource->parameter);

        $wrapper = $resource->parameter[0]->value;
        self::assertInstanceOf(DatePrimitive::class, $wrapper);

        $value = $wrapper->value;
        self::assertInstanceOf(FHIRTemporalValue::class, $value);
        self::assertNotNull($value->getParseError(), 'the failure must be recorded, not swallowed');
        self::assertSame('1900-02-29', (string) $value, 'the lexeme must survive exactly as written');
    }

    public function testUnparseableTemporalRoundTripsAsWritten(): void
    {
        $service  = FHIRSerializationService::createDefault(FhirVersion::R5);
        $resource = $service->deserialize(self::BAD);

        self::assertStringContainsString('1900-02-29', $service->serializeToXml($resource));
    }

    /**
     * The retry that treats an offset-less dateTime as UTC used to run unconditionally, so a value
     * that already carried an offset was reported as `…T12:59:60+10:00Z` — a string with two
     * offsets that never appeared in the document, misdirecting anyone debugging any dateTime.
     */
    public function testDateTimeDiagnosticNeverNamesAStringThatWasNotSupplied(): void
    {
        try {
            FHIRDateTime::parse('2013-01-01T12:59:60+10:00');
            self::fail('a leap second is not representable and must still raise');
        } catch (\Throwable $e) {
            self::assertStringNotContainsString('+10:00Z', $e->getMessage(), 'the retry must not invent a second offset');
        }
    }

    public function testDateTimeDiagnosticQuotesTheSuppliedLexeme(): void
    {
        try {
            FHIRDateTime::parse('2013-01-01T12:32:45+13:33.00');
            self::fail('a malformed offset must still raise');
        } catch (\Throwable $e) {
            self::assertStringContainsString('2013-01-01T12:32:45+13:33.00', $e->getMessage());
            self::assertStringNotContainsString('.00Z', $e->getMessage());
        }
    }

    /** The lenient UTC fallback for genuinely offset-less values must keep working. */
    public function testOffsetLessDateTimeStillParsesAsUtc(): void
    {
        self::assertSame('2020-11-11T10:58:14.768528', (string) FHIRDateTime::parse('2020-11-11T10:58:14.768528'));
    }
}
