<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemLookup\CodeSystemLookupInput as R4Input;
use Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemLookup\CodeSystemLookupOutput as R4Output;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemLookup\CodeSystemLookupInput as R5Input;
use Ardenexal\FHIRTools\Component\Models\R5\Operation\CodeSystemLookup\CodeSystemLookupOutput as R5Output;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRSerializedTypeResolver;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationMappingException;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\ProfiledParametersResource;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Proves one mapper serves R4 and R5 without version-specific logic.
 *
 * This is the milestone's kill criterion made executable: if a single mapper cannot serve both
 * versions with only its metadata differing, the "one generic mapper, N generated classes" premise
 * is wrong. Every test below runs against both versions through the same code path.
 *
 * Round-trip identity alone would prove the mapper is *a* bijection, not the *correct* one — an
 * identity function would pass. So the intermediate structure is asserted too: which slot each
 * value lands in, what the wire names are, and that the emitted parameters satisfy `inv-1`.
 *
 * ## Runs against M02's generated classes
 *
 * Written in M01 against hand-written stand-ins, repointed in M02 at
 * `Models\{version}\Operation\CodeSystemLookup\` with **no assertion changed** — only the class
 * resolution below. That is the milestone's strongest single result: the mapper and the classes it
 * maps were built a milestone apart and met without adjustment. The stand-ins survive as the oracle
 * {@see GeneratedMatchesHandWrittenTest} diffs against.
 *
 * The nested part classes are flat and `use`-prefixed here (`CodeSystemLookupOutProperty`, not
 * `CodeSystemLookupOutput\Property`), which is what makes N3's `property` in/out collision
 * impossible by construction rather than by luck.
 */
final class OperationParameterMapperTest extends TestCase
{
    /** Generated `$lookup` payloads live one namespace per version; `%s` takes R4 or R5. */
    private const string LOOKUP_NS = 'Ardenexal\FHIRTools\Component\Models\%s\Operation\CodeSystemLookup';

    /**
     * Every emitted parameter satisfies `inv-1`: exactly one of value, resource, part.
     *
     * A published invariant on the output type, so a construction bug that sets two slots (or none)
     * produces structurally invalid FHIR that still serializes.
     */
    #[DataProvider('versionProvider')]
    public function testEveryEmittedParameterSatisfiesInvariantOne(string $version, FhirVersion $fhirVersion): void
    {
        $parameters = OperationParameterMapper::createDefault($fhirVersion)->toParameters(self::populatedOutput($version));

        $checked = 0;

        foreach (self::flatten($parameters->parameter) as $parameter) {
            $slots = array_filter([
                'value'    => $parameter->value    ?? null,
                'resource' => $parameter->resource ?? null,
                'part'     => ($parameter->part ?? []) !== [] ? $parameter->part : null,
            ], static fn (mixed $slot): bool => $slot !== null);

            self::assertCount(
                1,
                $slots,
                sprintf(
                    'inv-1 violated on "%s": expected exactly one of value/resource/part, got [%s].',
                    (string) $parameter->name,
                    implode(', ', array_keys($slots)),
                ),
            );

            ++$checked;
        }

        self::assertGreaterThan(5, $checked, 'Fixture produced too few parameters to be a meaningful check.');
    }

    /**
     * Bare payload strings are wrapped into the primitive the FHIR type calls for.
     *
     * The payload declares `?string $display`, but `Parameters` resolves `value[x]` by matching the
     * runtime type against choice variants — a bare string cannot say whether it means `valueString`
     * or `valueCode`. Wrapping is what makes the emitted resource unambiguous.
     */
    #[DataProvider('versionProvider')]
    public function testBarePrimitivesAreWrappedOnTheWayOut(string $version, FhirVersion $fhirVersion): void
    {
        $parameters = OperationParameterMapper::createDefault($fhirVersion)->toParameters(self::populatedOutput($version));

        $display = self::parameterNamed($parameters->parameter, 'display');

        self::assertIsObject($display->value, '`display` was emitted as a bare value, not a primitive wrapper.');
        self::assertInstanceOf(
            sprintf('Ardenexal\FHIRTools\Component\Models\%s\Primitive\StringPrimitive', $version),
            $display->value,
        );
        self::assertSame('Left displacement', $display->value->value);
    }

    /**
     * A `max: '*'` parameter becomes repeated entries, not one entry holding an array.
     */
    #[DataProvider('versionProvider')]
    public function testCollectionsBecomeRepeatedParameters(string $version, FhirVersion $fhirVersion): void
    {
        $inputClass = self::inputClass($version);
        $input      = new $inputClass(code: 'A', system: 'http://loinc.org', property: ['parent', 'child', 'root']);

        $parameters = OperationParameterMapper::createDefault($fhirVersion)->toParameters($input);

        $properties = array_values(array_filter(
            $parameters->parameter,
            static fn (object $p): bool => (string) $p->name === 'property',
        ));

        self::assertCount(3, $properties, 'A max:"*" parameter must repeat, not nest an array.');

        foreach ($properties as $property) {
            self::assertIsObject($property->value);
            self::assertIsNotArray($property->value);
        }

        self::assertSame(['parent', 'child', 'root'], array_map(
            static fn (object $p): mixed => $p->value->value,
            $properties,
        ));
    }

    /**
     * Nested `part[]` groups recurse, and the nested wire names are preserved.
     */
    #[DataProvider('versionProvider')]
    public function testPartGroupsRecurse(string $version, FhirVersion $fhirVersion): void
    {
        $parameters = OperationParameterMapper::createDefault($fhirVersion)->toParameters(self::populatedOutput($version));

        $property = self::parameterNamed($parameters->parameter, 'property');

        self::assertNull($property->value, 'A part group must not also carry a value.');
        self::assertNotSame([], $property->part);

        $code = self::parameterNamed($property->part, 'code');
        self::assertSame('parent', $code->value->value);

        $subproperty = self::parameterNamed($property->part, 'subproperty');
        self::assertNotSame([], $subproperty->part, 'Nested part recursion stopped one level too early.');

        $nestedCode = self::parameterNamed($subproperty->part, 'code');
        self::assertSame('inherited', $nestedCode->value->value);
    }

    /**
     * A polymorphic value keeps the variant the caller chose, and lands on the right wire key.
     *
     * `resolveChoiceVariant` matches by `instanceof` in variant order, and the primitive wrappers
     * form real inheritance chains (CodePrimitive extends StringPrimitive). Passing a CodePrimitive
     * must therefore resolve to `valueCode`, not to `valueString`.
     */
    #[DataProvider('versionProvider')]
    public function testPolymorphicValueKeepsItsChosenVariant(string $version, FhirVersion $fhirVersion): void
    {
        $parameters = OperationParameterMapper::createDefault($fhirVersion)->toParameters(self::populatedOutput($version));

        $property = self::parameterNamed($parameters->parameter, 'property');

        // Outer level: a complex variant passes through as the Coding the caller supplied.
        $outerValue = self::parameterNamed($property->part, 'value');
        self::assertInstanceOf(
            sprintf('Ardenexal\FHIRTools\Component\Models\%s\DataType\Coding', $version),
            $outerValue->value,
        );

        // Nested level: a CodePrimitive must stay a CodePrimitive. Because resolveChoiceVariant
        // matches by instanceof in variant order and CodePrimitive extends StringPrimitive,
        // downgrading it here would silently emit `valueString`.
        $nested = self::parameterNamed($property->part, 'subproperty');
        $value  = self::parameterNamed($nested->part, 'value');

        self::assertInstanceOf(
            sprintf('Ardenexal\FHIRTools\Component\Models\%s\Primitive\CodePrimitive', $version),
            $value->value,
            'The polymorphic value lost the wrapper type the caller supplied.',
        );
        self::assertSame('inherited-from', $value->value->value);
    }

    /**
     * A bare scalar on a polymorphic parameter is refused, not guessed.
     */
    #[DataProvider('versionProvider')]
    public function testBareStringOnAPolymorphicParameterIsRefused(string $version, FhirVersion $fhirVersion): void
    {
        $propertyClass = sprintf(self::LOOKUP_NS . '\CodeSystemLookupOutProperty', $version);
        $outputClass   = self::outputClass($version);

        $output = new $outputClass(
            name: 'x',
            display: 'y',
            property: [new $propertyClass(code: 'p', value: 'ambiguous')],
        );

        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/polymorphic/');

        OperationParameterMapper::createDefault($fhirVersion)->toParameters($output);
    }

    /**
     * A missing `min: 1` parameter is reported rather than silently emitted as invalid FHIR.
     *
     * Generated model classes make every property nullable regardless of cardinality, so a
     * cardinality-invalid object constructs and passes static analysis. The mapper is the layer that
     * has to notice.
     */
    #[DataProvider('versionProvider')]
    public function testMissingRequiredParameterIsReported(string $version, FhirVersion $fhirVersion): void
    {
        $outputClass = self::outputClass($version);

        // `name` and `display` are both min:1 in the definition; omit `display`.
        $output = new $outputClass(name: 'ACME Codes');

        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/"display" is required/');

        OperationParameterMapper::createDefault($fhirVersion)->toParameters($output);
    }

    /**
     * `false` and `0` are values, not absences — omitting them would silently drop data.
     */
    #[DataProvider('versionProvider')]
    public function testFalsyValuesAreNotTreatedAsAbsent(string $version, FhirVersion $fhirVersion): void
    {
        $subpropertyClass = sprintf(self::LOOKUP_NS . '\CodeSystemLookupOutPropertySubproperty', $version);
        $propertyClass    = sprintf(self::LOOKUP_NS . '\CodeSystemLookupOutProperty', $version);
        $outputClass      = self::outputClass($version);

        $output = new $outputClass(
            name: 'n',
            display: 'd',
            property: [new $propertyClass(
                code: 'p',
                subproperty: [new $subpropertyClass(code: 'flag', value: false)],
            )],
        );

        $parameters  = OperationParameterMapper::createDefault($fhirVersion)->toParameters($output);
        $property    = self::parameterNamed($parameters->parameter, 'property');
        $subproperty = self::parameterNamed($property->part, 'subproperty');
        $value       = self::parameterNamed($subproperty->part, 'value');

        self::assertFalse($value->value, 'A false value was dropped as if absent.');
    }

    /**
     * The full round trip returns an equal payload, with the typed intermediate carrying real values.
     */
    #[DataProvider('versionProvider')]
    public function testRoundTripThroughParametersPreservesThePayload(string $version, FhirVersion $fhirVersion): void
    {
        $mapper   = OperationParameterMapper::createDefault($fhirVersion);
        $original = self::populatedOutput($version);

        $parameters = $mapper->toParameters($original);
        $restored   = $mapper->fromParameters($parameters, $original::class);

        self::assertEquals($original, $restored, 'Payload did not survive the round trip through Parameters.');
    }

    /**
     * Intermediate typed assertions — round-trip identity alone would pass for an identity function.
     */
    #[DataProvider('versionProvider')]
    public function testRestoredPayloadCarriesTheRightValuesAtTheRightPaths(string $version, FhirVersion $fhirVersion): void
    {
        $mapper   = OperationParameterMapper::createDefault($fhirVersion);
        $original = self::populatedOutput($version);

        $restored = $mapper->fromParameters($mapper->toParameters($original), $original::class);

        self::assertSame('ACME Codes', $restored->name);
        self::assertSame('Left displacement', $restored->display);
        self::assertCount(1, $restored->property);
        self::assertSame('parent', $restored->property[0]->code);
        self::assertCount(1, $restored->property[0]->subproperty);
        self::assertSame('inherited', $restored->property[0]->subproperty[0]->code);
        self::assertSame(
            'inherited-from',
            $restored->property[0]->subproperty[0]->value->value,
            'The nested polymorphic value did not survive at property[0].subproperty[0].value.',
        );
    }

    /**
     * The IN direction round-trips too, including the R5-only parameter R4 does not have.
     */
    #[DataProvider('versionProvider')]
    public function testInputRoundTripsIncludingCollections(string $version, FhirVersion $fhirVersion): void
    {
        $mapper     = OperationParameterMapper::createDefault($fhirVersion);
        $inputClass = self::inputClass($version);

        $input = new $inputClass(
            code: '1234',
            system: 'http://acme.org/cs',
            property: ['parent', 'child'],
        );

        $restored = $mapper->fromParameters($mapper->toParameters($input), $inputClass);

        self::assertSame('1234', $restored->code);
        self::assertSame(['parent', 'child'], $restored->property);
        self::assertEquals($input, $restored);
    }

    /**
     * The wrapped values serialize to the correct `value[x]` keys.
     *
     * Every other test here asserts object structure, which cannot catch a wrapping mistake: a
     * `StringPrimitive` where a `CodePrimitive` belongs produces a structurally identical graph and
     * the wrong wire format. This runs the emitted resource through the real serializer and reads
     * the keys back.
     */
    #[DataProvider('versionProvider')]
    public function testEmittedParametersSerializeToTheCorrectValueKeys(string $version, FhirVersion $fhirVersion): void
    {
        $parameters = OperationParameterMapper::createDefault($fhirVersion)->toParameters(self::populatedOutput($version));

        $json = FHIRSerializationService::createDefault($fhirVersion)->serializeToJson($parameters);

        /** @var array{parameter: list<array<string, mixed>>} $decoded */
        $decoded = json_decode($json, true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame('Parameters', $decoded['resourceType'] ?? null);

        $byName = array_column($decoded['parameter'], null, 'name');

        self::assertSame('ACME Codes', $byName['name']['valueString'] ?? null, '`name` did not emit as valueString.');
        self::assertSame('Left displacement', $byName['display']['valueString'] ?? null);
        self::assertArrayNotHasKey('value', $byName['property'], 'A part group must not emit a value.');

        $propertyParts = array_column($byName['property']['part'], null, 'name');

        self::assertSame('parent', $propertyParts['code']['valueCode'] ?? null, '`code` did not emit as valueCode.');
        self::assertArrayHasKey('valueCoding', $propertyParts['value'], 'A Coding did not emit as valueCoding.');

        $nested = array_column($propertyParts['subproperty']['part'], null, 'name');

        self::assertSame(
            'inherited-from',
            $nested['value']['valueCode'] ?? null,
            'The nested CodePrimitive emitted under the wrong key — probably downgraded to valueString.',
        );
    }

    /**
     * R4 and R5 produce byte-identical JSON from the same mapper for the same logical payload.
     *
     * The milestone's thesis stated as an assertion: the versions differ in the metadata attached to
     * generated classes, not in the mapper. Where the definitions genuinely differ ($lookup's
     * R5-only parameters), the payloads differ — this uses only the parameters common to both.
     */
    public function testR4AndR5EmitIdenticalJsonForACommonPayload(): void
    {
        $json = [];

        foreach (['R4' => FhirVersion::R4, 'R5' => FhirVersion::R5] as $version => $fhirVersion) {
            // One mapper class, wired per version. The mapper is version-scoped at construction now
            // (like the normalizers), so identical output here means the difference lives entirely
            // in the resolved metadata rather than in any branch the mapper takes.
            $parameters     = OperationParameterMapper::createDefault($fhirVersion)
                ->toParameters(self::populatedOutput($version));
            $json[$version] = FHIRSerializationService::createDefault($fhirVersion)->serializeToJson($parameters);
        }

        self::assertSame(
            $json['R4'],
            $json['R5'],
            'One mapper produced different wire output per version — the metadata-only premise is broken.',
        );
    }

    /**
     * The payload survives a trip through real JSON, not just through in-memory objects.
     *
     * This is the path a real invocation takes and it differs in a way that matters:
     * `toParameters()` sets `ParametersParameter::$name` to a bare string, but deserializing the
     * same resource from JSON produces a `StringPrimitive`. A mapper that compared names with `===`
     * against a string would match nothing here while every in-memory test stayed green.
     */
    #[DataProvider('versionProvider')]
    public function testPayloadSurvivesAFullJsonRoundTrip(string $version, FhirVersion $fhirVersion): void
    {
        $mapper          = OperationParameterMapper::createDefault($fhirVersion);
        $service         = FHIRSerializationService::createDefault($fhirVersion);
        $original        = self::populatedOutput($version);
        $parametersClass = sprintf('Ardenexal\FHIRTools\Component\Models\%s\Resource\ParametersResource', $version);

        $json = $service->serializeToJson($mapper->toParameters($original));

        $deserialized = $service->deserializeFromJson($json, $parametersClass);

        // Guard the premise: if names stop arriving wrapped, this test no longer covers what it claims.
        self::assertIsObject(
            $deserialized->parameter[0]->name,
            'Deserialized parameter names are no longer StringPrimitive — re-check wireName().',
        );

        $restored = $mapper->fromParameters($deserialized, $original::class);

        self::assertSame('ACME Codes', $restored->name);
        self::assertSame('Left displacement', $restored->display);
        self::assertSame('parent', $restored->property[0]->code);
        self::assertSame('inherited', $restored->property[0]->subproperty[0]->code);
        self::assertSame('inherited-from', $restored->property[0]->subproperty[0]->value->value);
    }

    /**
     * A profiled `Parameters` registered with the type resolver is produced instead of the base class.
     *
     * This is why the mapper resolves through the registry rather than interpolating
     * `Models\{version}\Resource\ParametersResource`. A hardcoded namespace can only ever emit
     * base-spec classes, so an IG profile — or any application-level `addResourceTypeMapping()` —
     * would be honoured by the rest of the serializer but silently ignored here.
     */
    public function testARegisteredProfileClassIsUsedInsteadOfTheBaseResource(): void
    {
        $resolver = new FHIRSerializedTypeResolver(
            resourceTypeMapping: ['Parameters' => ProfiledParametersResource::class],
            fhirVersion: FhirVersion::R4->value,
        );

        $parameters = (new OperationParameterMapper($resolver))->toParameters(self::populatedOutput('R4'));

        self::assertInstanceOf(
            ProfiledParametersResource::class,
            $parameters,
            'The mapper built the base Parameters class and ignored the registered profile.',
        );

        // The profile must still be a working Parameters — the backbone type is read from whichever
        // class the resolver returned, so the mapping itself has to keep functioning.
        self::assertNotSame([], $parameters->parameter);
        self::assertSame('ACME Codes', (string) $parameters->parameter[0]->value);
    }

    /**
     * A class with no operation metadata is rejected rather than silently producing an empty resource.
     */
    public function testNonOperationClassIsRejected(): void
    {
        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/not an operation payload/');

        OperationParameterMapper::createDefault(FhirVersion::R4)->toParameters(new \stdClass());
    }

    /**
     * @return iterable<string, array{string, FhirVersion}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4' => ['R4', FhirVersion::R4];
        yield 'R5' => ['R5', FhirVersion::R5];
    }

    /**
     * An Output populated deeply enough to exercise nesting, collections and the polymorphic slot.
     */
    private static function populatedOutput(string $version): R4Output|R5Output
    {
        $codePrimitive = sprintf('Ardenexal\FHIRTools\Component\Models\%s\Primitive\CodePrimitive', $version);
        $coding        = sprintf('Ardenexal\FHIRTools\Component\Models\%s\DataType\Coding', $version);
        $base          = sprintf(self::LOOKUP_NS, $version);

        $subpropertyClass = $base . '\CodeSystemLookupOutPropertySubproperty';
        $propertyClass    = $base . '\CodeSystemLookupOutProperty';
        $outputClass      = $base . '\CodeSystemLookupOutput';

        return new $outputClass(
            name: 'ACME Codes',
            version: '2026-01',
            display: 'Left displacement',
            property: [
                new $propertyClass(
                    code: 'parent',
                    // A complex variant at the outer level and a primitive one nested, so both
                    // arms of resolveChoiceVariant (instanceof and builtin) are exercised.
                    value: new $coding(display: 'Parent of'),
                    description: 'Parent concept',
                    subproperty: [
                        new $subpropertyClass(
                            code: 'inherited',
                            value: new $codePrimitive(value: 'inherited-from'),
                            description: 'Inherited from parent',
                        ),
                    ],
                ),
            ],
        );
    }

    /**
     * @return class-string<R4Input|R5Input>
     */
    private static function inputClass(string $version): string
    {
        /** @var class-string<R4Input|R5Input> */
        return sprintf(self::LOOKUP_NS . '\CodeSystemLookupInput', $version);
    }

    /**
     * @return class-string<R4Output|R5Output>
     */
    private static function outputClass(string $version): string
    {
        /** @var class-string<R4Output|R5Output> */
        return sprintf(self::LOOKUP_NS . '\CodeSystemLookupOutput', $version);
    }

    /**
     * @param list<object> $parameters
     */
    private static function parameterNamed(array $parameters, string $name): object
    {
        foreach ($parameters as $parameter) {
            if ((string) $parameter->name === $name) {
                return $parameter;
            }
        }

        self::fail(sprintf('No parameter named "%s" was emitted.', $name));
    }

    /**
     * @param list<object> $parameters
     *
     * @return list<object>
     */
    private static function flatten(array $parameters): array
    {
        $flat = [];

        foreach ($parameters as $parameter) {
            $flat[] = $parameter;
            $flat   = [...$flat, ...self::flatten($parameter->part ?? [])];
        }

        return $flat;
    }
}
