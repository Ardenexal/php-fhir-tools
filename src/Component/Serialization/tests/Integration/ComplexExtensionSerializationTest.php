<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtensionRecorder;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\AlternateHashExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\PatAnimalExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
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

    public function testXmlMissingRequiredSubExtensionReadsAsAPlainExtension(): void
    {
        $xml = <<<'XML'
            <Patient xmlns="http://hl7.org/fhir">
              <extension url="http://hl7.org/fhir/StructureDefinition/patient-animal">
                <extension url="breed">
                  <valueCodeableConcept><text value="Poodle"/></valueCodeableConcept>
                </extension>
              </extension>
              <active value="true"/>
            </Patient>
            XML;

        $patient = $this->service->deserializeFromXml($xml, PatientResource::class);

        self::assertInstanceOf(PatientResource::class, $patient);
        $this->assertReadAsPlainPatientAnimal($patient);
        self::assertTrue($patient->active);
    }

    public function testJsonMissingRequiredSubExtensionReadsAsAPlainExtension(): void
    {
        $json = <<<'JSON'
            {
              "resourceType": "Patient",
              "extension": [{
                "url": "http://hl7.org/fhir/StructureDefinition/patient-animal",
                "extension": [{"url": "breed", "valueCodeableConcept": {"text": "Poodle"}}]
              }],
              "active": true
            }
            JSON;

        $patient = $this->service->deserializeFromJson($json, PatientResource::class);

        self::assertInstanceOf(PatientResource::class, $patient);
        $this->assertReadAsPlainPatientAnimal($patient);
        self::assertTrue($patient->active);
    }

    /**
     * The typed constructor rebuilds the list from its declared slices only, which used to drop
     * everything else. An undeclared slice and a nested extension are both kept, and written back.
     */
    public function testSubExtensionsTheTypedClassDoesNotDeclareAreKept(): void
    {
        $json = '{"url":"http://hl7.org/fhir/StructureDefinition/patient-animal","extension":['
            . '{"url":"species","valueCodeableConcept":{"text":"Dog"}},'
            . '{"url":"species-x","valueString":"unknown"},'
            . '{"url":"http://hl7.org/fhir/StructureDefinition/bodySite","valueReference":{"display":"tail"}}]}';

        $read = $this->service->deserializeFromJson($json, PatAnimalExtension::class);

        self::assertInstanceOf(PatAnimalExtension::class, $read);
        self::assertSame(
            ['species', 'species-x', 'http://hl7.org/fhir/StructureDefinition/bodySite'],
            array_map(static fn (Extension $sub): ?string => $sub->url, $read->extension),
        );
        // Equal, not identical: key order within an object is not part of the contract.
        self::assertEquals(
            json_decode($json, true, 512, JSON_THROW_ON_ERROR),
            json_decode($this->service->serializeToJson($read), true, 512, JSON_THROW_ON_ERROR),
        );
    }

    /**
     * Each level is read once. Re-reading the raw sub-extensions after the typed build failed
     * doubled the work per level, so twenty nested levels took about a million reads.
     */
    public function testNestedIncompleteComplexExtensionsAreEachReadOnce(): void
    {
        $depth = 20;
        $inner = ['url' => 'breed', 'valueCodeableConcept' => ['text' => 'Poodle']];
        for ($i = 0; $i < $depth; ++$i) {
            $inner = ['url' => 'http://hl7.org/fhir/StructureDefinition/patient-animal', 'extension' => [$inner]];
        }
        $json = json_encode(['resourceType' => 'Patient', 'extension' => [$inner]], JSON_THROW_ON_ERROR);

        $started = microtime(true);
        $patient = $this->service->deserializeFromJson($json, PatientResource::class);
        self::assertLessThan(5.0, microtime(true) - $started);

        self::assertInstanceOf(PatientResource::class, $patient);
        $level = $patient->extension[0];
        for ($i = 1; $i < $depth; ++$i) {
            self::assertSame(Extension::class, $level::class);
            self::assertCount(1, IncompleteComplexExtensionRecorder::forObject($level));
            $level = $level->extension[0];
        }
    }

    /**
     * Asking for the typed class itself cannot be answered with a plain Extension, so the read
     * still refuses, naming the missing slice.
     */
    public function testAskingForTheTypedClassStillRefusesAMissingRequiredSubExtension(): void
    {
        $json = '{"url":"http://hl7.org/fhir/StructureDefinition/patient-animal","extension":[{"url":"breed","valueCodeableConcept":{"text":"Poodle"}}]}';

        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessage('Required sub-extension "species" not found');

        $this->service->deserializeFromJson($json, PatAnimalExtension::class);
    }

    /**
     * The extension is kept, as the plain Extension it still is, with nothing dropped, and the
     * typed class it could not become is recorded against it for validation to report.
     */
    private function assertReadAsPlainPatientAnimal(PatientResource $patient): void
    {
        self::assertCount(1, $patient->extension);
        $extension = $patient->extension[0];

        self::assertSame(Extension::class, $extension::class);
        self::assertNotInstanceOf(FHIRComplexExtensionInterface::class, $extension);
        self::assertSame('http://hl7.org/fhir/StructureDefinition/patient-animal', $extension->url);
        self::assertCount(1, $extension->extension);
        self::assertSame('breed', $extension->extension[0]->url);

        $records = IncompleteComplexExtensionRecorder::forObject($extension);
        self::assertCount(1, $records);
        self::assertSame(PatAnimalExtension::class, $records[0]->extensionClass);
        self::assertSame('species', $records[0]->sliceName);
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
