<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\RelatedPersonResource;
use Ardenexal\FHIRTools\Component\Sdc\Extract\DefinitionPathWriter;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Focused unit coverage for the production {@see DefinitionPathWriter} primitives introduced for M02's
 * `extractAllocateId` (single-valued complex descent) and typed `definitionExtractValue` (scalar →
 * primitive-wrapper coercion). These paths are exercised end-to-end by the extract conformance tests;
 * this pins them directly, without a network oracle.
 */
final class DefinitionPathWriterTest extends TestCase
{
    private DefinitionPathWriter $writer;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->writer     = new DefinitionPathWriter(new PropertyMetadataProvider());
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testDescendsIntoSingleValuedComplexIntermediate(): void
    {
        // RelatedPerson.patient is a non-array `?Reference` whose #[FhirProperty] carries no
        // phpItemClass; the writer must reflect the declared type to instantiate the Reference.
        $relatedPerson = new RelatedPersonResource();

        $this->writer->writeLeaf($relatedPerson, ['patient', 'reference'], 'urn:uuid:abc-123');

        self::assertInstanceOf(Reference::class, $relatedPerson->patient);
        self::assertSame('urn:uuid:abc-123', $relatedPerson->patient->reference);
    }

    public function testCoercesRawScalarIntoDeclaredPrimitiveWrapper(): void
    {
        // Identifier.system is a `?UriPrimitive`: a raw calculated string must be wrapped, not assigned
        // as a bare string (which would violate the property type and fail to serialize).
        $patient = new PatientResource();

        $this->writer->writeLeaf($patient, ['identifier', 'system'], 'http://example.org/mrn');

        $identifier = $patient->identifier[0] ?? null;
        self::assertNotNull($identifier);
        self::assertInstanceOf(UriPrimitive::class, $identifier->system);
        self::assertSame('http://example.org/mrn', $identifier->system->value);

        $decoded = json_decode($this->serializer->serializeToJson($patient), true);
        self::assertIsArray($decoded);
        self::assertSame('http://example.org/mrn', $decoded['identifier'][0]['system'] ?? null);
    }

    public function testLeavesRawScalarUntouchedWhenDeclaredTypeAcceptsIt(): void
    {
        // Identifier.value is `StringPrimitive|string|null`: the union already accepts a raw string, so
        // it must NOT be wrapped (mirrors how answered values pass through unchanged).
        $patient = new PatientResource();

        $this->writer->writeLeaf($patient, ['identifier', 'value'], '12345');

        $identifier = $patient->identifier[0] ?? null;
        self::assertNotNull($identifier);
        self::assertSame('12345', $identifier->value);
    }

    public function testLeavesNonStringScalarRawWhenDeclaredTypeIsABuiltin(): void
    {
        // Patient.active is `?bool` (a builtin, not a primitive wrapper class): a calculated boolean must
        // be stored raw, exercising the builtin-match branch for a non-string scalar. In R4 the numeric
        // and boolean primitives are builtin-typed like this, so scalar wrapping only ever applies to the
        // string-based primitive classes (uri, date, code, …).
        $patient = new PatientResource();

        $this->writer->writeLeaf($patient, ['active'], true);

        self::assertTrue($patient->active);
    }
}
