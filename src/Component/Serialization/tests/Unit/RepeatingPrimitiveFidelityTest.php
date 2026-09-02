<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use PHPUnit\Framework\TestCase;

/**
 * A repeating primitive survives XML with the right number of occurrences, and JSON keeps its shape.
 *
 * Two defects met on `Meta.profile` and both are asserted here at the level they were observable —
 * a document in, a document out — because both were invisible to every unit-level assertion in the
 * suite while they shipped.
 *
 * @author Ardenexal
 */
class RepeatingPrimitiveFidelityTest extends TestCase
{
    private const string PROFILE_A = 'http://example.org/StructureDefinition/A';

    private const string PROFILE_B = 'http://example.org/StructureDefinition/B';

    /**
     * One occurrence of a repeating element is one entry, not two.
     *
     * `XmlEncoder` decodes a lone `<profile value="X"/>` to that element's own map,
     * `['@value' => 'X', '#' => '']`, and only decodes two or more occurrences to a list. The
     * denormalizer iterated the map's keys, so one element produced two primitives: 'X' from
     * `@value` and '' from `#`. Two occurrences produced two, which is why this looked correct.
     */
    public function testOneOccurrenceOfARepeatingPrimitiveYieldsOneEntry(): void
    {
        $patient = $this->deserializeXmlWithProfiles(self::PROFILE_A);

        self::assertCount(1, $patient->meta->profile);
        self::assertSame(self::PROFILE_A, $patient->meta->profile[0]->value);
    }

    /**
     * The counts that framed the defect: one in one out, two in two out, three in three out.
     */
    public function testOccurrenceCountIsPreservedForEveryArity(): void
    {
        self::assertCount(1, $this->deserializeXmlWithProfiles(self::PROFILE_A)->meta->profile);
        self::assertCount(2, $this->deserializeXmlWithProfiles(self::PROFILE_A, self::PROFILE_B)->meta->profile);
        self::assertCount(
            3,
            $this->deserializeXmlWithProfiles(self::PROFILE_A, self::PROFILE_B, 'http://example.org/C')->meta->profile,
        );
    }

    /**
     * A lone occurrence keeps its child extension.
     *
     * The key walk consumed the element's `extension` key as if it were another occurrence, so the
     * extension was dropped along with the count being wrong.
     */
    public function testALoneOccurrenceKeepsItsChildExtension(): void
    {
        $xml = '<Patient xmlns="http://hl7.org/fhir"><id value="p"/><meta>'
            . '<profile value="' . self::PROFILE_A . '">'
            . '<extension url="http://example.org/ext"><valueString value="note"/></extension>'
            . '</profile></meta></Patient>';

        $patient = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($xml);

        self::assertCount(1, $patient->meta->profile);
        self::assertNotEmpty($patient->meta->profile[0]->extension);
    }

    /**
     * Serializing to XML first must not change the JSON that follows.
     *
     * FHIR requires `"profile": ["…"]`. Serializing the same service to XML first produced
     * `[{"value": "…"}]` — invalid FHIR JSON — because the XML pass asked `isComplexType()` about
     * the primitive before anything asked `isPrimitiveType()`, and the two questions shared one
     * cache slot. The damage was per-service, so in a long-running app one XML serialization
     * poisoned every later `meta.profile` JSON in the process.
     */
    public function testSerializingToXmlFirstDoesNotChangeTheJsonThatFollows(): void
    {
        $json = '{"resourceType":"Patient","id":"p","meta":{"profile":["' . self::PROFILE_A . '","' . self::PROFILE_B . '"]}}';

        $jsonFirst = FHIRSerializationService::createDefault(FhirVersion::R4);
        $expected  = $jsonFirst->serializeToJson($jsonFirst->deserialize($json));

        $xmlFirst = FHIRSerializationService::createDefault(FhirVersion::R4);
        $resource = $xmlFirst->deserialize($json);
        $xmlFirst->serializeToXml($resource);

        $afterXml = $xmlFirst->serializeToJson($resource);

        self::assertSame($expected, $afterXml);

        // Spelled out rather than left to the string comparison: FHIR requires a bare list of
        // strings here, and the defect produced a list of {"value": …} objects.
        self::assertSame(
            [self::PROFILE_A, self::PROFILE_B],
            json_decode($afterXml, true)['meta']['profile'],
        );
    }

    /**
     * One object never serialized to XML is still damaged by another that was, on the same service.
     *
     * This is what makes the defect a service-lifetime problem rather than a per-object one, and it
     * is the assertion that would have caught it in a framework app.
     */
    public function testAnUntouchedObjectIsUnaffectedByAnotherObjectsXmlSerialization(): void
    {
        $json = '{"resourceType":"Patient","id":"p","meta":{"profile":["' . self::PROFILE_A . '"]}}';

        $service     = FHIRSerializationService::createDefault(FhirVersion::R4);
        $serialized  = $service->deserialize($json);
        $neverAsXml  = $service->deserialize($json);

        $service->serializeToXml($serialized);

        self::assertSame(
            [self::PROFILE_A],
            json_decode($service->serializeToJson($neverAsXml), true)['meta']['profile'],
        );
    }

    /**
     * A primitive is classified the same way whichever structural question is asked first.
     *
     * `CanonicalPrimitive` carries `#[FHIRPrimitive]` itself and inherits `#[FHIRComplexType]` from
     * `Element`, so both predicates walk to a positive answer independently. They shared one cache
     * slot, so the first question asked won it and the answers contradicted each other by call
     * order. A primitive is never a complex type; asking in either order must say so.
     */
    public function testPrimitiveClassificationDoesNotDependOnQuestionOrder(): void
    {
        $primitive = new CanonicalPrimitive(value: self::PROFILE_A);

        $primitiveFirst = new FHIRMetadataExtractor();
        self::assertTrue($primitiveFirst->isPrimitiveType($primitive));
        self::assertFalse($primitiveFirst->isComplexType($primitive));

        $complexFirst = new FHIRMetadataExtractor();
        self::assertFalse($complexFirst->isComplexType($primitive));
        self::assertTrue($complexFirst->isPrimitiveType($primitive));
    }

    /**
     * Read a Patient whose `meta` carries one `<profile>` element per given URL.
     */
    private function deserializeXmlWithProfiles(string ...$profiles): object
    {
        $elements = '';
        foreach ($profiles as $profile) {
            $elements .= '<profile value="' . $profile . '"/>';
        }

        $xml = '<Patient xmlns="http://hl7.org/fhir"><id value="p"/><meta>' . $elements . '</meta></Patient>';

        return FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($xml);
    }
}
