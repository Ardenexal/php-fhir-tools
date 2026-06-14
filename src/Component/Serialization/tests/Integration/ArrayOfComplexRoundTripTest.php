<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * M02b contract test: array-of-complex properties (e.g. Patient.name = [HumanName, HumanName]) must
 * round-trip with full fidelity. This is the behaviour the PHPStan-"dead" denormalize branch
 * (`$phpItemClass !== null && is_array($value)`) claims to handle; it must keep working through the
 * type-flow restructure, in both JSON and XML.
 */
final class ArrayOfComplexRoundTripTest extends TestCase
{
    private const string PATIENT_JSON = <<<'JSON'
{
  "resourceType": "Patient",
  "id": "example",
  "name": [
    { "family": "Smith", "given": ["John", "Q"] },
    { "family": "Jones" }
  ]
}
JSON;

    public function testArrayOfComplexJsonRoundTripPreservesAllItems(): void
    {
        $service = FHIRSerializationService::createDefault();

        $patient    = $service->deserialize(self::PATIENT_JSON);
        $reJson     = $service->serializeToJson($patient);
        $decoded    = json_decode($reJson, true);

        self::assertIsArray($decoded);
        self::assertArrayHasKey('name', $decoded);
        self::assertIsArray($decoded['name']);
        self::assertCount(2, $decoded['name'], 'both HumanName entries must survive the round-trip');
        self::assertSame('Smith', $decoded['name'][0]['family'] ?? null);
        self::assertSame(['John', 'Q'], $decoded['name'][0]['given'] ?? null);
        self::assertSame('Jones', $decoded['name'][1]['family'] ?? null);
    }

    public function testArrayOfComplexXmlRoundTripPreservesAllItems(): void
    {
        $service = FHIRSerializationService::createDefault();

        $patient = $service->deserialize(self::PATIENT_JSON);
        $xml     = $service->serializeToXml($patient);
        $back    = $service->deserialize($xml);

        // Read name array via reflection (no public typed accessor on the model).
        $names = (new \ReflectionClass($back))->getProperty('name')->getValue($back);
        self::assertIsArray($names);
        self::assertCount(2, $names, 'both HumanName entries must survive a JSON→object→XML→object trip');
    }

    /**
     * Regression guard: a repeating field with exactly ONE value must survive an XML round-trip.
     * XmlEncoder collapses a lone element to a scalar/assoc array rather than a list, so single-element
     * arrays must be re-wrapped on deserialization. Covers both a repeating primitive (`given`) and a
     * repeating complex type (`identifier`). The 2+-element case is covered above; this guards the tail.
     */
    public function testSingleElementRepeatingFieldsXmlRoundTrip(): void
    {
        $service = FHIRSerializationService::createDefault();

        $json = <<<'JSON'
{
  "resourceType": "Patient",
  "id": "single",
  "identifier": [ { "system": "urn:x", "value": "123" } ],
  "name": [ { "family": "Smith", "given": ["John"] } ]
}
JSON;

        $patient = $service->deserialize($json);
        $xml     = $service->serializeToXml($patient);
        $back    = $service->deserialize($xml);

        $refl = new \ReflectionClass($back);

        $names = $refl->getProperty('name')->getValue($back);
        self::assertIsArray($names);
        self::assertCount(1, $names, 'single HumanName must survive an XML round-trip');

        $given = (new \ReflectionClass($names[0]))->getProperty('given')->getValue($names[0]);
        self::assertIsArray($given, 'single-element repeating primitive `given` must stay an array');
        self::assertCount(1, $given, 'lone `given` value must not be lost when XML collapses it');

        $identifiers = $refl->getProperty('identifier')->getValue($back);
        self::assertIsArray($identifiers);
        self::assertCount(1, $identifiers, 'single complex `identifier` must survive an XML round-trip');
    }
}
