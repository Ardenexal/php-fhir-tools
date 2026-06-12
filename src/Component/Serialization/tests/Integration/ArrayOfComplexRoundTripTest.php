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
}
