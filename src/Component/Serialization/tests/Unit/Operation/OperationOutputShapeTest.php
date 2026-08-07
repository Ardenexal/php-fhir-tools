<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationMappingException;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReportResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSetResource;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\CodeSystemLookupOperation as R4Lookup;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\MeasureSubmitDataInput;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\MeasureSubmitDataOperation as R4SubmitData;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\ResourceGraphOperation as R4Graph;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5\CodeSystemLookupOperation as R5Lookup;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Proves the class-B (bare-resource) output shape, on `ValueSet/$expand`.
 *
 * `$lookup` alone would have proven the minority case. Only 26–30% of core operations answer with a
 * genuine `Parameters` resource; **57% of R4 and 64% of R5** declare a sole OUT parameter named
 * `return` that is resource-typed, and the specification is literal about what that means
 * (`hl7.org/fhir/R4/operations.html`, and identically in R5):
 *
 * > "If there is only one _out_ parameter, which is a Resource with the parameter name "return"
 * > then the parameter format is not used, and the response is simply the resource itself."
 *
 * That settles M00's O1 residual: the response is genuinely un-wrapped, not
 * wrapped-but-conventionally-unwrapped. A mapper that ran `fromParameters()` over such a body would
 * be wrong for the majority of operations — hence the shape discriminator on the holder.
 *
 * `$expand` was chosen over `Patient/$everything` because it also closes two gaps `$lookup` left:
 * its `valueSet` IN parameter is **resource-typed**, exercising the `resource` slot that previously
 * shipped with zero coverage; and four of its parameters carry hyphens on the wire, so the wire
 * name and the PHP identifier genuinely diverge.
 */
final class OperationOutputShapeTest extends TestCase
{
    /**
     * A class-B response is the resource itself — returned as-is, never run through the mapper.
     */
    #[DataProvider('versionProvider')]
    public function testBareResourceResponseIsTheResourceItself(string $version, FhirVersion $fhirVersion): void
    {
        $valueSetClass = self::valueSetClass($version);
        $expanded      = new $valueSetClass(id: 'expanded-1', name: 'ACME Codes expansion');

        $output = OperationParameterMapper::createDefault($fhirVersion)
            ->fromResponse($expanded, self::expandOperation($version));

        self::assertSame(
            $expanded,
            $output,
            'A bare-resource response must pass through untouched — the response IS the resource.',
        );
    }

    /**
     * A server that wrapped the resource in `Parameters` anyway is rejected, not silently accepted.
     *
     * This is the assertion that makes the O1 answer load-bearing. If the shape were
     * "wrapped-but-conventionally-unwrapped", accepting a `Parameters` body here would be correct.
     * It is not, so a `Parameters` arriving for a class-B operation is a contract violation.
     */
    #[DataProvider('versionProvider')]
    public function testParametersWrappedClassBResponseIsRejected(string $version, FhirVersion $fhirVersion): void
    {
        $mapper          = OperationParameterMapper::createDefault($fhirVersion);
        $parametersClass = self::parametersClass($version);

        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/bare-resource/');

        $mapper->fromResponse(new $parametersClass(), self::expandOperation($version));
    }

    /**
     * A class-A operation still goes through the Parameters mapping when read via the same seam.
     *
     * Proves the discriminator actually discriminates: one entry point, two behaviours, chosen by
     * metadata rather than by inspecting the body.
     */
    #[DataProvider('versionProvider')]
    public function testParametersShapedOperationStillMapsThroughParameters(string $version, FhirVersion $fhirVersion): void
    {
        $mapper      = OperationParameterMapper::createDefault($fhirVersion);
        $outputClass = sprintf(
            'Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\%s\CodeSystemLookupOutput',
            $version,
        );

        $original = new $outputClass(name: 'ACME Codes', display: 'Left displacement');
        $body     = $mapper->toParameters($original);

        $restored = $mapper->fromResponse($body, $version === 'R4' ? R4Lookup::class : R5Lookup::class);

        self::assertInstanceOf($outputClass, $restored, 'A class-A response must be mapped, not passed through.');
        self::assertSame('ACME Codes', $restored->name);
    }

    /**
     * `toResponse()` is the inverse and respects the same discriminator.
     */
    #[DataProvider('versionProvider')]
    public function testToResponseMirrorsTheShape(string $version, FhirVersion $fhirVersion): void
    {
        $mapper        = OperationParameterMapper::createDefault($fhirVersion);
        $valueSetClass = self::valueSetClass($version);
        $expanded      = new $valueSetClass(id: 'expanded-1');

        self::assertSame($expanded, $mapper->toResponse($expanded, self::expandOperation($version)));

        $outputClass = sprintf(
            'Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\%s\CodeSystemLookupOutput',
            $version,
        );
        $parameters = $mapper->toResponse(
            new $outputClass(name: 'n', display: 'd'),
            $version === 'R4' ? R4Lookup::class : R5Lookup::class,
        );

        self::assertInstanceOf(self::parametersClass($version), $parameters);
    }

    /**
     * A payload class handed to the response seam is rejected — the holder carries the shape.
     */
    public function testNonHolderClassIsRejected(): void
    {
        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/#\[FhirOperation\]/');

        OperationParameterMapper::createDefault(FhirVersion::R4)->fromResponse(null, \stdClass::class);
    }

    /**
     * A resource-typed IN parameter lands in the `resource` slot, not in `value[x]`.
     *
     * This branch of the mapper shipped with zero coverage: none of `$lookup`'s parameters is
     * resource-typed, so `isResourceType()` and the `resource:` construction path were written but
     * never executed. `$expand`'s `valueSet` parameter is typed `ValueSet`, which exercises both.
     */
    #[DataProvider('versionProvider')]
    public function testResourceTypedParameterTakesTheResourceSlot(string $version, FhirVersion $fhirVersion): void
    {
        $valueSetClass = self::valueSetClass($version);
        $inputClass    = self::expandInputClass($version);
        $supplied      = new $valueSetClass(id: 'to-expand', name: 'Supplied inline');

        $parameters = OperationParameterMapper::createDefault($fhirVersion)
            ->toParameters(new $inputClass(valueSet: $supplied, count: 50));

        $valueSet = self::parameterNamed($parameters->parameter, 'valueSet');

        self::assertSame($supplied, $valueSet->resource, 'A resource-typed parameter must use the resource slot.');
        self::assertNull($valueSet->value, 'inv-1: a parameter with a resource must not also carry a value.');
        self::assertSame([], $valueSet->part ?? []);

        // The scalar sibling still takes value[x], so this is a per-parameter decision rather than a
        // whole-payload mode.
        $count = self::parameterNamed($parameters->parameter, 'count');
        self::assertNull($count->resource);
        self::assertNotNull($count->value);
    }

    /**
     * The resource slot survives a real JSON round trip, inline resource and all.
     */
    #[DataProvider('versionProvider')]
    public function testResourceSlotSurvivesJsonRoundTrip(string $version, FhirVersion $fhirVersion): void
    {
        $mapper        = OperationParameterMapper::createDefault($fhirVersion);
        $service       = FHIRSerializationService::createDefault($fhirVersion);
        $valueSetClass = self::valueSetClass($version);
        $inputClass    = self::expandInputClass($version);

        $input = new $inputClass(valueSet: new $valueSetClass(id: 'to-expand', name: 'Supplied inline'));

        $json = $service->serializeToJson($mapper->toParameters($input));

        /** @var array{parameter: list<array<string, mixed>>} $decoded */
        $decoded   = json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
        $onTheWire = array_column($decoded['parameter'], null, 'name')['valueSet'];

        self::assertArrayHasKey('resource', $onTheWire, 'The resource slot did not serialize as `resource`.');
        self::assertArrayNotHasKey('valueValueSet', $onTheWire, 'A resource must never emit as a value[x] key.');
        self::assertSame('ValueSet', $onTheWire['resource']['resourceType'] ?? null);

        $restored = $mapper->fromParameters(
            $service->deserializeFromJson($json, self::parametersClass($version)),
            $inputClass,
        );

        self::assertInstanceOf($valueSetClass, $restored->valueSet, 'The inline resource was lost on the way back.');
        self::assertSame('to-expand', $restored->valueSet->id);
    }

    /**
     * Hyphenated wire names survive intact — the PHP identifier is never emitted on the wire.
     *
     * `exclude-system` and friends are not legal PHP identifiers, so the fixture declares them as
     * `$excludeSystem`. Round-tripping requires the wire name verbatim; a generator that normalised
     * or "corrected" it would produce parameters no server recognises.
     */
    #[DataProvider('versionProvider')]
    public function testHyphenatedWireNamesAreEmittedVerbatim(string $version, FhirVersion $fhirVersion): void
    {
        $mapper     = OperationParameterMapper::createDefault($fhirVersion);
        $inputClass = self::expandInputClass($version);

        $input = new $inputClass(
            excludeSystem:      ['http://loinc.org|2.48'],
            systemVersion:      ['http://snomed.info/sct|http://snomed.info/sct/900000000000207008'],
            checkSystemVersion: ['http://acme.org/cs|1.0'],
            forceSystemVersion: ['http://acme.org/cs|2.0'],
        );

        $emitted = array_map(
            static fn (object $p): string => (string) $p->name,
            $mapper->toParameters($input)->parameter,
        );

        self::assertSame(
            ['exclude-system', 'system-version', 'check-system-version', 'force-system-version'],
            $emitted,
            'A PHP identifier leaked onto the wire in place of the declared parameter name.',
        );

        $restored = $mapper->fromParameters($mapper->toParameters($input), $inputClass);

        self::assertSame(['http://loinc.org|2.48'], $restored->excludeSystem);
        self::assertEquals($input, $restored);
    }

    /**
     * Every `$expand` IN parameter matches the published definition — same guard as `$lookup`.
     *
     * The fixture was transcribed from the definition mechanically, which removes the risk of a
     * typo but not the risk of a systematically wrong rule. Diffing against the source catches both.
     */
    #[DataProvider('versionProvider')]
    public function testExpandInputMatchesTheDefinition(string $version, FhirVersion $fhirVersion): void
    {
        $definition = self::definition($version);
        $expected   = array_values(array_filter(
            $definition['parameter'],
            static fn (array $p): bool => $p['use'] === 'in',
        ));

        $actual = [];

        foreach ((new \ReflectionClass(self::expandInputClass($version)))->getProperties() as $property) {
            foreach ($property->getAttributes(FhirOperationParameter::class) as $attribute) {
                $instance                = $attribute->newInstance();
                $actual[$instance->name] = $instance;
            }
        }

        self::assertSame(
            array_column($expected, 'name'),
            array_keys($actual),
            'The $expand IN parameters differ from the published definition in membership or order.',
        );

        foreach ($expected as $parameter) {
            $attribute = $actual[$parameter['name']];

            self::assertSame($parameter['use'], $attribute->use);
            self::assertSame($parameter['min'], $attribute->min);
            self::assertSame((string) $parameter['max'], $attribute->max);
            self::assertSame($parameter['type'] ?? null, $attribute->type);
        }
    }

    /**
     * The holder's shape is read off the definition, not assumed: sole OUT, named `return`, resource.
     */
    #[DataProvider('versionProvider')]
    public function testHolderDeclaresTheShapeTheDefinitionImplies(string $version, FhirVersion $fhirVersion): void
    {
        $definition = self::definition($version);
        $out        = array_values(array_filter(
            $definition['parameter'],
            static fn (array $p): bool => $p['use'] === 'out',
        ));

        self::assertCount(1, $out, 'The class-B premise is a *sole* OUT parameter.');
        self::assertSame('return', $out[0]['name']);
        self::assertSame('ValueSet', $out[0]['type']);

        $holder = (new \ReflectionClass(self::expandOperation($version)))
            ->getAttributes(FhirOperation::class)[0]->newInstance();

        self::assertSame(OperationOutputShape::BareResource, $holder->outputShape);
        self::assertSame(
            self::valueSetClass($version),
            $holder->outputClass,
            'outputClass must point at the resource itself — there is no generated Output class.',
        );
        self::assertNull(
            $holder->outputParameterName,
            'BareResource is `return` by definition; the name is only retained for NamedBareResource.',
        );
        self::assertSame($definition['instance'], $holder->instance);
        self::assertSame($definition['type'], $holder->type);
        self::assertSame($definition['system'] ?? false, $holder->system);
    }

    /**
     * Class C is **wrapped**, because the un-wrap rule is conditioned on the name `return`.
     *
     * `Resource/$graph` returns a sole `Bundle` OUT parameter named `result`. It fails the rule's
     * name condition, so the parameter format is used after all and the Bundle arrives inside a
     * one-parameter `Parameters`. Collapsing class C into class B — which the first cut of the
     * response seam did — reads a wrapped body as though it were bare.
     */
    public function testClassCResponseIsWrappedInParametersUnderItsDeclaredName(): void
    {
        $mapper = OperationParameterMapper::createDefault(FhirVersion::R4);
        $bundle = new BundleResource(id: 'graph-result');

        $body = $mapper->toResponse($bundle, R4Graph::class);

        self::assertInstanceOf(self::parametersClass('R4'), $body, 'A class-C response must be wrapped.');
        self::assertCount(1, $body->parameter);
        self::assertSame('result', (string) $body->parameter[0]->name, 'The declared name was not used.');
        self::assertSame($bundle, $body->parameter[0]->resource);

        self::assertSame($bundle, $mapper->fromResponse($body, R4Graph::class), 'The wrapper did not unwrap.');
    }

    /**
     * A class-C body missing its named parameter is reported rather than returning null.
     */
    public function testClassCResponseMissingItsNamedParameterIsReported(): void
    {
        $mapper          = OperationParameterMapper::createDefault(FhirVersion::R4);
        $parametersClass = self::parametersClass('R4');
        $parameterClass  = 'Ardenexal\FHIRTools\Component\Models\R4\Resource\Parameters\ParametersParameter';

        $body = new $parametersClass(parameter: [
            new $parameterClass(name: 'somethingElse', resource: new BundleResource()),
        ]);

        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/no parameter named "result"/');

        $mapper->fromResponse($body, R4Graph::class);
    }

    /**
     * Class C and class B are not interchangeable, in either direction.
     *
     * The assertion that would have caught the collapsed implementation: the same `Bundle` produces
     * a wrapped body for `$graph` and a bare one for a class-B operation.
     */
    public function testClassCAndClassBProduceDifferentBodies(): void
    {
        $mapper   = OperationParameterMapper::createDefault(FhirVersion::R4);
        $valueSet = new ValueSetResource(id: 'expanded');

        $classB = $mapper->toResponse($valueSet, self::expandOperation('R4'));
        $classC = $mapper->toResponse(new BundleResource(id: 'g'), R4Graph::class);

        self::assertSame($valueSet, $classB, 'Class B must stay bare.');
        self::assertInstanceOf(self::parametersClass('R4'), $classC, 'Class C must be wrapped.');
    }

    /**
     * Class D: no OUT parameters, so a successful invocation yields null — not an empty object.
     */
    public function testNoOutputShapeYieldsNull(): void
    {
        $mapper = OperationParameterMapper::createDefault(FhirVersion::R4);

        self::assertNull($mapper->fromResponse(null, R4SubmitData::class));
        self::assertNull($mapper->toResponse(null, R4SubmitData::class));
    }

    /**
     * A body arriving for a no-output operation is a contract violation, not something to ignore.
     *
     * Keeps "succeeded, no body" distinguishable from "failed to parse" — the reason class D is
     * modelled explicitly rather than as an Output that happened to come back empty.
     */
    public function testNoOutputShapeRejectsABody(): void
    {
        $parametersClass = self::parametersClass('R4');

        $this->expectException(OperationMappingException::class);
        $this->expectExceptionMessageMatches('/empty body/');

        OperationParameterMapper::createDefault(FhirVersion::R4)
            ->fromResponse(new $parametersClass(), R4SubmitData::class);
    }

    /**
     * A repeated resource-typed parameter typed with the abstract `Resource` takes the resource slot.
     *
     * `$submit-data`'s `resource` is `0..*` and typed `Resource`, which is the one case
     * `isResourceType()` answers without asking the registry — no concrete class is registered under
     * that name. Nothing else in the fixture set covers either a repeated resource slot or that arm.
     */
    public function testRepeatedAbstractResourceParameterTakesTheResourceSlot(): void
    {
        $mapper = OperationParameterMapper::createDefault(FhirVersion::R4);

        $input = new MeasureSubmitDataInput(
            measureReport: new MeasureReportResource(id: 'mr-1'),
            resource: [new ValueSetResource(id: 'vs-1'), new BundleResource(id: 'b-1')],
        );

        $parameters = $mapper->toParameters($input);

        $resources = array_values(array_filter(
            $parameters->parameter,
            static fn (object $p): bool => (string) $p->name === 'resource',
        ));

        self::assertCount(2, $resources, 'A max:"*" resource parameter must repeat.');

        foreach ($resources as $parameter) {
            self::assertNotNull($parameter->resource, 'A Resource-typed parameter must use the resource slot.');
            self::assertNull($parameter->value);
        }

        self::assertEquals($input, $mapper->fromParameters($parameters, MeasureSubmitDataInput::class));
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
     * @return array<string, mixed>
     */
    private static function definition(string $version): array
    {
        $file = sprintf(
            '%s/../../Fixtures/OperationDefinitions/%s-ValueSet-expand.json',
            __DIR__,
            strtolower($version),
        );

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Definition fixture %s is unreadable.', $file));

        /** @var array<string, mixed> $definition */
        $definition = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        return $definition;
    }

    /**
     * @return class-string
     */
    private static function expandOperation(string $version): string
    {
        /** @var class-string */
        return sprintf(
            'Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\%s\ValueSetExpandOperation',
            $version,
        );
    }

    /**
     * @return class-string
     */
    private static function expandInputClass(string $version): string
    {
        /** @var class-string */
        return sprintf(
            'Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\%s\ValueSetExpandInput',
            $version,
        );
    }

    /**
     * @return class-string
     */
    private static function valueSetClass(string $version): string
    {
        /** @var class-string */
        return sprintf('Ardenexal\FHIRTools\Component\Models\%s\Resource\ValueSetResource', $version);
    }

    /**
     * @return class-string
     */
    private static function parametersClass(string $version): string
    {
        /** @var class-string */
        return sprintf('Ardenexal\FHIRTools\Component\Models\%s\Resource\ParametersResource', $version);
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
}
