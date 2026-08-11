<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * A leading UTF-8 BOM must not defeat deserialization.
 *
 * A BOM is legal at the start of a UTF-8 document and 15 files in the vendored FHIR test corpus carry
 * one. It used to break two distinct layers, which is why every entry point is covered here rather
 * than just `detectFormat()`:
 *
 *  - `trim()`'s default charlist excludes `EF BB BF`, so format auto-detection threw
 *    "Unable to detect data format".
 *  - `json_decode()` rejects a leading BOM outright, so `detectTargetClass()` threw
 *    "Unable to detect target class from data" even once the format was known.
 *
 * XML never depended on the fix — libxml consumes the BOM itself — but it is asserted so a future
 * change to the strip cannot silently regress the XML path.
 */
final class ByteOrderMarkDeserializationTest extends TestCase
{
    private const BOM = "\xEF\xBB\xBF";

    private const JSON = '{"resourceType":"Patient","id":"bom-json"}';

    private const XML = '<Patient xmlns="http://hl7.org/fhir"><id value="bom-xml"/></Patient>';

    private FHIRSerializationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testAutoDetectDeserializesBomPrefixedJson(): void
    {
        $patient = $this->service->deserialize(self::BOM . self::JSON);

        self::assertInstanceOf(PatientResource::class, $patient);
        // Asserting the value, not merely the absence of an exception: a strip that removed too many
        // bytes would still construct an object, just a wrong one.
        self::assertSame('bom-json', $patient->id);
    }

    public function testAutoDetectDeserializesBomPrefixedXml(): void
    {
        $patient = $this->service->deserialize(self::BOM . self::XML);

        self::assertInstanceOf(PatientResource::class, $patient);
        self::assertSame('bom-xml', $patient->id);
    }

    public function testBomPrefixedInputMatchesPlainInput(): void
    {
        $withBom    = $this->service->deserialize(self::BOM . self::JSON);
        $withoutBom = $this->service->deserialize(self::JSON);

        self::assertInstanceOf(PatientResource::class, $withBom);
        self::assertInstanceOf(PatientResource::class, $withoutBom);
        self::assertEquals($withoutBom, $withBom);
    }

    public function testDeserializeFromJsonAcceptsBomDirectly(): void
    {
        // deserializeFromJson() is a public entry point in its own right, so it cannot rely on
        // deserialize() having stripped the BOM first.
        $patient = $this->service->deserializeFromJson(self::BOM . self::JSON, PatientResource::class);

        self::assertSame('bom-json', $patient->id);
    }

    public function testDeserializeFromXmlAcceptsBomDirectly(): void
    {
        $patient = $this->service->deserializeFromXml(self::BOM . self::XML, PatientResource::class);

        self::assertSame('bom-xml', $patient->id);
    }

    public function testOnlyASingleLeadingBomIsStripped(): void
    {
        // A second BOM is payload, not framing. It must remain and produce a parse failure rather than
        // being quietly consumed — otherwise the strip would be masking malformed input.
        $this->expectException(\Throwable::class);

        $this->service->deserialize(self::BOM . self::BOM . self::JSON);
    }
}
