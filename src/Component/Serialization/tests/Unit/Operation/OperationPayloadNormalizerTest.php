<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRSerializedTypeResolver;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIROperationPayloadJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIROperationPayloadXmlNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Proves generated operation payloads work through the plain Symfony Serializer.
 *
 * This is the integration surface a framework actually uses. API Platform's `input:` option hands
 * the request body to the Symfony Serializer with the target class, so a generated Input class only
 * works there if the serializer knows how to build one:
 *
 * ```php
 * #[Post(
 *     uriTemplate: '/ValueSet/{id}/$validate-code',
 *     input:       ValueSetValidateCodeInput::class,
 *     processor:   ValueSetValidateCodeProcessor::class,
 * )]
 * ```
 *
 * ## The failure this prevents is silent
 *
 * Without {@see FHIROperationPayloadJsonNormalizer}, `ObjectNormalizer` claims the class and looks for
 * constructor arguments named `url`, `code` and `system` in a body shaped
 * `{"resourceType":"Parameters","parameter":[…]}`. It finds none, throws nothing, and returns an
 * object with **every property null** — so the processor runs as though the client sent an empty
 * request. `testWithoutTheNormalizerEveryPropertyIsSilentlyNull` pins that, because the whole
 * argument for this class is that the alternative fails quietly rather than loudly.
 */
final class OperationPayloadNormalizerTest extends TestCase
{
    private const string VALIDATE_CODE_REQUEST = <<<'JSON'
        {
          "resourceType": "Parameters",
          "parameter": [
            { "name": "url", "valueUri": "http://acme.org/vs" },
            { "name": "code", "valueCode": "chol-mmol" },
            { "name": "system", "valueUri": "http://acme.org/cs" }
          ]
        }
        JSON;

    /**
     * A `Parameters` request body deserializes into a populated typed Input.
     */
    #[DataProvider('versionProvider')]
    public function testOperationRequestBodyDeserializesIntoTheTypedInput(string $version, FhirVersion $fhirVersion): void
    {
        $input = self::serializer($fhirVersion)->deserialize(
            self::VALIDATE_CODE_REQUEST,
            self::inputClass($version),
            'json',
        );

        self::assertSame('http://acme.org/vs', $input->url);
        self::assertSame('chol-mmol', $input->code);
        self::assertSame('http://acme.org/cs', $input->system);
    }

    /**
     * A typed Output serializes back to a conformant `Parameters` resource.
     */
    #[DataProvider('versionProvider')]
    public function testTypedOutputSerializesToAParametersResource(string $version, FhirVersion $fhirVersion): void
    {
        $outputClass = self::outputClass($version);
        $output      = new $outputClass(result: true, message: 'Code is valid');

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode(
            self::serializer($fhirVersion)->serialize($output, 'json'),
            true,
            512,
            \JSON_THROW_ON_ERROR,
        );

        self::assertSame('Parameters', $decoded['resourceType'] ?? null);

        $byName = array_column($decoded['parameter'], null, 'name');

        // `result` is min:1 boolean — it must emit as valueBoolean, not valueString.
        self::assertTrue($byName['result']['valueBoolean'] ?? null, '`result` did not emit as valueBoolean.');
        self::assertSame('Code is valid', $byName['message']['valueString'] ?? null);
    }

    /**
     * The full request-then-response cycle a handler sees, in one test.
     */
    #[DataProvider('versionProvider')]
    public function testFullRequestResponseCycle(string $version, FhirVersion $fhirVersion): void
    {
        $serializer = self::serializer($fhirVersion);

        $input = $serializer->deserialize(self::VALIDATE_CODE_REQUEST, self::inputClass($version), 'json');

        // What a processor would do with it.
        $outputClass = self::outputClass($version);
        $output      = new $outputClass(result: $input->code === 'chol-mmol', display: 'Cholesterol');

        $body = $serializer->serialize($output, 'json');

        self::assertStringContainsString('"valueBoolean":true', $body);
        self::assertStringContainsString('"name":"display"', $body);
    }

    /**
     * **The failure mode this class exists to prevent**, pinned as observed behaviour.
     *
     * With only `ObjectNormalizer` — the default a framework falls back to — the payload comes back
     * with every property null and nothing is raised. If this ever starts throwing instead, the
     * silence argument weakens and the docblocks should be revisited.
     */
    public function testWithoutTheNormalizerEveryPropertyIsSilentlyNull(): void
    {
        $bare  = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $input = $bare->deserialize(self::VALIDATE_CODE_REQUEST, self::inputClass('R5'), 'json');

        self::assertNull($input->url, 'ObjectNormalizer now populates the payload — re-check this class.');
        self::assertNull($input->code);
        self::assertNull($input->system);
    }

    /**
     * The normalizer claims payload classes and nothing else.
     *
     * It sits first in the chain, so over-claiming would divert ordinary resources away from the
     * FHIR normalizers.
     */
    public function testItClaimsOnlyOperationPayloads(): void
    {
        $json = new FHIROperationPayloadJsonNormalizer(new FHIRMetadataExtractor(), new FHIRSerializedTypeResolver(), version: 'R5');
        $xml  = new FHIROperationPayloadXmlNormalizer(new FHIRMetadataExtractor(), new FHIRSerializedTypeResolver(), version: 'R5');

        self::assertTrue($json->supportsDenormalization([], self::inputClass('R5'), 'json'));
        self::assertTrue($json->supportsDenormalization([], self::outputClass('R5'), 'json'));

        self::assertFalse(
            $json->supportsDenormalization([], 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSetResource', 'json'),
            'A plain resource must stay with the FHIR normalizers.',
        );
        self::assertFalse($json->supportsDenormalization([], \stdClass::class, 'json'));
        self::assertFalse($json->supportsDenormalization([], 'No\Such\Class', 'json'));
        self::assertFalse($json->supportsNormalization(new \stdClass(), 'json'));

        // Format gating mirrors every other normalizer pair: each declines the other's format, so
        // exactly one claims a given payload and neither can shadow the other.
        self::assertFalse($json->supportsDenormalization([], self::inputClass('R5'), 'xml'));
        self::assertTrue($xml->supportsDenormalization([], self::inputClass('R5'), 'xml'));
        self::assertFalse($xml->supportsDenormalization([], self::inputClass('R5'), 'json'));
    }

    /**
     * A payload from another FHIR version is refused, not mapped against the wrong models.
     *
     * Every normalizer in this chain is version-scoped at construction, and
     * `FHIRSerializationService` builds one chain per version. Without this check an R4 payload
     * reaching an R5 chain would be mapped against R5's `Parameters` and R5 primitives, failing
     * later with a type error that says nothing about the real cause.
     */
    public function testPayloadFromAnotherVersionIsRefused(): void
    {
        $normalizer = new FHIROperationPayloadJsonNormalizer(
            new FHIRMetadataExtractor(),
            new FHIRSerializedTypeResolver(fhirVersion: 'R5'),
            version: 'R5',
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/is a R4 operation payload, but this serializer is scoped to R5/');

        $normalizer->denormalize(['resourceType' => 'Parameters'], self::inputClass('R4'), 'json');
    }

    /**
     * The XML leg works through the same seam, with no format-specific code behind it.
     *
     * The two subclasses differ only in which `$format` they accept; the shared base maps to a
     * `Parameters` resource and delegates rendering. This is that claim under test rather than
     * asserted — XML renders `value[x]` as an element name and primitives as attributes, so a
     * payload path that had quietly grown a JSON assumption would fail here.
     */
    #[DataProvider('versionProvider')]
    public function testPayloadRoundTripsThroughXml(string $version, FhirVersion $fhirVersion): void
    {
        $service     = FHIRSerializationService::createDefault($fhirVersion);
        $outputClass = self::outputClass($version);
        $original    = new $outputClass(result: true, message: 'Code is valid');

        $xml = $service->serializeToXml($original);

        self::assertStringContainsString('<Parameters', $xml);
        self::assertStringContainsString('valueBoolean value="true"', $xml, 'The boolean lost its element in XML.');

        $restored = $service->deserializeFromXml($xml, $outputClass);

        self::assertInstanceOf($outputClass, $restored);
        self::assertTrue($restored->result);
        self::assertSame('Code is valid', $restored->message);
    }

    /**
     * A resource-shaped (class-B) response needs no help — the FHIR normalizers already handle it.
     *
     * ~60% of operations answer with a bare resource and have no generated Output class, so a
     * handler returns the resource itself. This confirms adding the payload normalizer to the front
     * of the chain did not disturb that path.
     */
    public function testBareResourceResponsesAreUntouched(): void
    {
        $service  = FHIRSerializationService::createDefault(FhirVersion::R5);
        $valueSet = new ('Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSetResource')(id: 'expanded');

        $json = $service->serializeToJson($valueSet);

        self::assertStringContainsString('"resourceType":"ValueSet"', $json);
        self::assertStringContainsString('"id":"expanded"', $json);
    }

    /**
     * @return iterable<string, array{string, FhirVersion}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4'  => ['R4', FhirVersion::R4];
        yield 'R4B' => ['R4B', FhirVersion::R4B];
        yield 'R5'  => ['R5', FhirVersion::R5];
    }

    /**
     * A serializer wired the way an application would wire one: the FHIR service's own chain, with
     * `ObjectNormalizer` last as the framework fallback.
     */
    private static function serializer(FhirVersion $version): Serializer
    {
        $service = FHIRSerializationService::createDefault($version);

        // Reach the inner Serializer so this exercises the real registered chain — including the
        // ordering — rather than a hand-assembled one that could differ from production.
        $inner = (new \ReflectionProperty($service, 'serializer'))->getValue($service);
        self::assertInstanceOf(Serializer::class, $inner);

        /** @var list<object> $normalizers */
        $normalizers = (new \ReflectionProperty($inner, 'normalizers'))->getValue($inner);

        return new Serializer([...$normalizers, new ObjectNormalizer()], [new JsonEncoder()]);
    }

    /**
     * @return class-string
     */
    private static function inputClass(string $version): string
    {
        /** @var class-string */
        return sprintf(
            'Ardenexal\FHIRTools\Component\Models\%s\Operation\ValueSetValidateCode\ValueSetValidateCodeInput',
            $version,
        );
    }

    /**
     * @return class-string
     */
    private static function outputClass(string $version): string
    {
        /** @var class-string */
        return sprintf(
            'Ardenexal\FHIRTools\Component\Models\%s\Operation\ValueSetValidateCode\ValueSetValidateCodeOutput',
            $version,
        );
    }
}
