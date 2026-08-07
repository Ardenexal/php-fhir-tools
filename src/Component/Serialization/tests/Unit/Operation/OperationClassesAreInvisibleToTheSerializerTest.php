<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\CodeSystemLookupInput;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\CodeSystemLookupOutput;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\ValueSetExpandInput;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Settles D2's open caveat: operation payload classes are invisible to the serializer's metadata layer.
 *
 * D2 asserts that generated operation classes must NOT carry `#[FhirResource]`/`#[FhirProperty]`,
 * because `FHIRResourceJsonNormalizer` would then walk their properties and emit flat keys
 * (`{"code":"...","system":"..."}`) instead of a parameter array. It also flagged a caveat to verify
 * rather than assume: *if `FHIRMetadataExtractor` builds its cache by scanning a directory, operation
 * classes landing under `src/Component/Models/src/` would be pulled into that scan.*
 *
 * **The caveat does not apply, and the reason is structural rather than locational.**
 * `FHIRMetadataExtractor` has no directory scan at all — every entry point takes an
 * `object $object` and reflects on that instance on demand (`extractResourceType`, `extractFHIRType`,
 * `isResource`, …). Nothing enumerates a namespace or a path. So an operation class is only ever
 * examined if something hands the extractor an instance of one, and the mapper never does: it hands
 * over a `Parameters` resource it just built.
 *
 * That makes D2's conclusion independent of where M02 puts the generated classes, which is the
 * useful form of the answer — the M01 fixtures live under `tests/`, but M02's output will not, and a
 * location-based argument would have expired at that point.
 *
 * What remains load-bearing is the *negative* half: the classes must carry no resource metadata. That
 * is what this file asserts, because it is the half a generator can silently break.
 */
final class OperationClassesAreInvisibleToTheSerializerTest extends TestCase
{
    /**
     * An operation payload class is not a resource, a complex type, or a backbone element.
     *
     * If any of these flipped to true, the normalizers' `supports*()` checks would start claiming
     * these objects and emit flat keys instead of a `Parameters` body.
     */
    #[DataProvider('payloadClassProvider')]
    public function testOperationPayloadIsNotAnyKindOfFhirType(object $payload): void
    {
        $extractor = new FHIRMetadataExtractor();

        self::assertNull(
            $extractor->extractResourceType($payload),
            'An operation payload class reports a resourceType — it has picked up #[FhirResource].',
        );
        self::assertNull($extractor->extractFHIRType($payload));
        self::assertNull($extractor->extractFHIRVersion($payload));

        self::assertFalse($extractor->isResource($payload));
        self::assertFalse($extractor->isComplexType($payload));
        self::assertFalse($extractor->isPrimitiveType($payload));
        self::assertFalse($extractor->isBackboneElement($payload));
    }

    /**
     * Handing a payload class straight to the serializer is refused outright.
     *
     * This is the failure D2 exists to prevent, stated as a behaviour instead of a rule. The danger
     * D2 names was never an exception — it was flat, well-formed-looking JSON
     * (`{"code":"A","system":"..."}`) that is not a `Parameters` resource and that no server would
     * accept. Refusal is therefore the *ideal* outcome, and the observed one: no normalizer claims
     * the object, because it carries none of the attributes their `supports*()` checks look for.
     *
     * Asserted as a thrown exception rather than as "the output does not look flat". An
     * absence-of-substring assertion would have been the weaker choice twice over — it passes
     * vacuously when the call throws, and `json_encode` escapes forward slashes by default, so
     * flat output would read `"system":"http:\/\/loinc.org"` and slip past a naive needle.
     */
    public function testSerializingAPayloadDirectlyIsRefused(): void
    {
        $service = FHIRSerializationService::createDefault(FhirVersion::R4);
        $payload = new CodeSystemLookupInput(code: 'A', system: 'http://loinc.org');

        $this->expectException(FHIRSerializationException::class);

        // Names the cause, not just the failure: the object is unclaimed by every normalizer. A
        // future change that made it serialize *and* throw for some other reason would not pass.
        $this->expectExceptionMessageMatches('/no supporting normalizer found/');

        $service->serializeToJson($payload);
    }

    /**
     * The extractor exposes no directory- or namespace-scanning entry point.
     *
     * The evidence behind this file's central claim, kept as an assertion so that adding a scan later
     * fails here rather than silently reopening D2's caveat.
     */
    public function testExtractorHasNoScanningEntryPoint(): void
    {
        $reflection = new \ReflectionClass(FHIRMetadataExtractor::class);

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            if (in_array($method->getName(), ['__construct', 'getPropertyMetadataProvider', 'getCache', 'clearCache'], true)) {
                continue;
            }

            $parameters = $method->getParameters();

            self::assertNotSame([], $parameters, sprintf('%s() takes no argument — is it a scan?', $method->getName()));
            self::assertSame(
                'object',
                (string) $parameters[0]->getType(),
                sprintf(
                    '%s() does not take an object. Every extractor entry point reflects on an instance '
                    . 'handed to it; one that takes a path or a namespace would be a scan, and D2\'s '
                    . 'caveat about operation classes being pulled into it would apply again.',
                    $method->getName(),
                ),
            );
        }
    }

    /**
     * @return iterable<string, array{object}>
     */
    public static function payloadClassProvider(): iterable
    {
        yield 'lookup input'  => [new CodeSystemLookupInput(code: 'A')];
        yield 'lookup output' => [new CodeSystemLookupOutput(name: 'n', display: 'd')];
        yield 'expand input'  => [new ValueSetExpandInput(url: 'http://acme.org/vs')];
    }
}
