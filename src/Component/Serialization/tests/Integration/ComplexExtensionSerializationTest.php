<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\AlternateHashExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * A typed complex extension writes its sub-extensions and nothing else.
 *
 * The generated class keeps each sub-extension's value twice: as the typed constructor property
 * (`algorithm`, `hash`) and as the sub-extension the constructor builds from it. Only the
 * sub-extension is FHIR; the typed property is not an element of Extension, so writing it as well
 * puts keys on the wire that no FHIR reader expects.
 */
#[CoversClass(FHIRSerializationService::class)]
final class ComplexExtensionSerializationTest extends TestCase
{
    private FHIRSerializationService $service;

    protected function setUp(): void
    {
        $this->service = FHIRSerializationService::createDefault();
    }

    public function testJsonWritesOnlyTheUrlAndSubExtensions(): void
    {
        $json = json_decode($this->service->serializeToJson($this->extension()), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($json);
        self::assertSame(['extension', 'url'], $this->sortedKeys($json));
        self::assertSame('http://hl7.org/fhir/StructureDefinition/alternate-hash', $json['url']);
        self::assertSame(
            [
                ['url' => 'algorithm', 'valueCoding' => ['system' => 'urn:ietf:rfc:3230', 'code' => 'sha-256']],
                ['url' => 'hash', 'valueString' => 'abc123'],
            ],
            $json['extension'],
        );
    }

    public function testXmlWritesOnlyTheUrlAndSubExtensions(): void
    {
        $xml = $this->service->serializeToXml($this->extension());

        self::assertStringNotContainsString('<algorithm', $xml);
        self::assertStringNotContainsString('<hash', $xml);
        self::assertStringContainsString('<extension url="algorithm">', $xml);
        self::assertStringContainsString('<extension url="hash">', $xml);
    }

    public function testJsonRoundTripRebuildsTheTypedProperties(): void
    {
        $json = $this->service->serializeToJson($this->extension());

        $read = $this->service->deserializeFromJson($json, AlternateHashExtension::class);

        self::assertInstanceOf(AlternateHashExtension::class, $read);
        self::assertSame('sha-256', (string) $read->algorithm->code);
        self::assertSame('abc123', (string) $read->hash);
    }

    private function extension(): AlternateHashExtension
    {
        return new AlternateHashExtension(
            algorithm: new Coding(system: new UriPrimitive(value: 'urn:ietf:rfc:3230'), code: new CodePrimitive(value: 'sha-256')),
            hash: new StringPrimitive(value: 'abc123'),
        );
    }

    /**
     * @param array<array-key, mixed> $data
     *
     * @return list<array-key>
     */
    private function sortedKeys(array $data): array
    {
        $keys = array_keys($data);
        sort($keys);

        return $keys;
    }
}
