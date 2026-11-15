<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * `contained` must deserialize to typed resources in JSON exactly as it already did in XML.
 *
 * For the life of the project it did not. `contained` is declared
 * `#[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true), Valid]` with the PHP
 * type `array`, and the generator cannot emit a `phpItemClass` for it because there is no single class
 * to name for a polymorphic `Resource`. The JSON array-denormalization branch was gated on
 * `phpItemClass !== null`, so `contained` fell through to the generic branch, where the declared type
 * is the builtin `array` and every item was kept as a raw array. `FHIRResourceXmlNormalizer` resolved
 * these all along, so the same document parsed to typed objects as XML and raw arrays as JSON.
 *
 * The consequence was silent and total: every rule that walks the object tree — the invariant cascade,
 * `NarrativeXhtmlChecker`, `PrimitiveFormatChecker`, `CodingSystemChecker`, `BundleEntryFullUrlChecker`
 * — was blind to contained resources in JSON documents. Nothing asserted this property, which is why
 * a format-parity divergence survived so long, so these tests assert the property directly rather than
 * any one rule's output.
 */
final class ContainedResourceFormatParityTest extends TestCase
{
    private const JSON_DOC = <<<'JSON'
        {
          "resourceType": "Patient",
          "id": "p1",
          "contained": [
            {"resourceType": "Medication", "id": "m1", "code": {"text": "aspirin"}}
          ],
          "active": true
        }
        JSON;

    private const XML_DOC = <<<'XML'
        <?xml version="1.0" encoding="UTF-8"?>
        <Patient xmlns="http://hl7.org/fhir">
            <id value="p1"/>
            <contained>
                <Medication>
                    <id value="m1"/>
                    <code><text value="aspirin"/></code>
                </Medication>
            </contained>
            <active value="true"/>
        </Patient>
        XML;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    /**
     * The property this milestone existed to restore: same document, two formats, one result.
     */
    public function testJsonAndXmlProduceIdenticalContainedTypes(): void
    {
        $fromJson = $this->serializer->deserialize(self::JSON_DOC);
        $fromXml  = $this->serializer->deserialize(self::XML_DOC);

        self::assertInstanceOf(PatientResource::class, $fromJson);
        self::assertInstanceOf(PatientResource::class, $fromXml);

        self::assertSame(
            array_map(get_class(...), $fromXml->contained),
            array_map(get_class(...), $fromJson->contained),
            'JSON and XML must deserialize `contained` to the same resource types.',
        );
    }

    public function testJsonContainedIsATypedResourceNotARawArray(): void
    {
        $patient = $this->serializer->deserialize(self::JSON_DOC);
        self::assertInstanceOf(PatientResource::class, $patient);

        // The concrete class is resolved from the item's own `resourceType` through the type resolver,
        // so a profile-backed registry can substitute its own class here. Asserting the base-spec class
        // is correct for a default service.
        self::assertInstanceOf(MedicationResource::class, $patient->contained[0] ?? null);
    }

    /**
     * Typed objects in `contained` must still round-trip. While `contained` held raw arrays this worked
     * by accident — the array was handed back out untouched — so it is genuinely new behaviour and the
     * JSON normalizer has no `propertyKind === 'resource'` branch on the serialize side.
     *
     * @return iterable<string, array{0: string}>
     */
    public static function documentProvider(): iterable
    {
        yield 'parsed from JSON' => [self::JSON_DOC];
        yield 'parsed from XML'  => [self::XML_DOC];
    }

    #[DataProvider('documentProvider')]
    public function testContainedSurvivesRoundTripInBothFormats(string $document): void
    {
        $original = $this->serializer->deserialize($document);

        $json = $this->serializer->serializeToJson($original);
        $xml  = $this->serializer->serializeToXml($original);

        $decoded = json_decode($json, true);
        self::assertIsArray($decoded);
        self::assertSame(
            'Medication',
            $decoded['contained'][0]['resourceType'] ?? null,
            'A typed contained resource must serialize with its resourceType, or it cannot be read back.',
        );
        // The serializer re-declares the FHIR namespace on the contained element
        // (`<Medication xmlns="http://hl7.org/fhir">`), so match the element name, not a bare tag.
        self::assertMatchesRegularExpression('/<Medication[\s>]/', $xml);

        // Re-reading our own output must yield the same types — the property above, applied to the
        // serializer's output rather than only to the corpus fixtures.
        $reparsedJson = $this->serializer->deserialize($json);
        $reparsedXml  = $this->serializer->deserialize($xml);

        self::assertInstanceOf(MedicationResource::class, $reparsedJson->contained[0] ?? null);
        self::assertInstanceOf(MedicationResource::class, $reparsedXml->contained[0] ?? null);
    }

    /**
     * An unresolvable `resourceType` keeps the raw array rather than dropping the item. Silently
     * deleting a contained resource would be a worse failure than leaving it opaque, and opaque is
     * exactly the behaviour that held here before.
     */
    public function testUnresolvableContainedResourceIsKeptRatherThanDropped(): void
    {
        $patient = $this->serializer->deserialize(<<<'JSON'
            {
              "resourceType": "Patient",
              "id": "p1",
              "contained": [{"resourceType": "NotARealResourceType", "id": "x1"}]
            }
            JSON);

        self::assertInstanceOf(PatientResource::class, $patient);
        self::assertCount(1, $patient->contained, 'The item must not be dropped.');
        self::assertIsArray($patient->contained[0]);
    }
}
