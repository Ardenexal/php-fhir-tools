<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Diffs M02's generated operation classes against M01's hand-written ones, pair by pair.
 *
 * M01 hand-wrote `$lookup`, `$expand`, `$submit-data` and `$graph` payloads to prove the mapper
 * before a generator existed, and {@see OperationFixtureFidelityTest} proves those fixtures faithful
 * to the published OperationDefinitions. That chain is what makes this file worth writing: the
 * fixtures are an *independently verified* target, so agreeing with them is evidence about the
 * generator rather than two transcriptions of the same mistake. It is also why the fixtures stay —
 * deleting them would delete the oracle.
 *
 * Run this before repointing any behavioural test. A behavioural test that passes against generated
 * classes says the pair is close enough for that test's assertions; this says where they differ at
 * all, including in the fields no behavioural test happens to read.
 *
 * ## What is compared, and what is deliberately not
 *
 * Compared: parameter membership, declaration **order** (`Parameters.parameter` is an ordered wire
 * list, so order is a contract — M02 states this explicitly), `use`, `min`, `max`, `type`, the
 * variant set, and `partClass` — the last resolved and compared by *its* parameters, since the two
 * trees legitimately use different FQCNs (`…\CodeSystemLookupOutput\Designation` against
 * `…\Operation\CodeSystemLookup\CodeSystemLookupOutDesignation`).
 *
 * Not compared: `documentation`. The fixtures abbreviate it and add trailing full stops; the
 * generator emits the published text verbatim. Excluded explicitly rather than silently omitted, so
 * a reader does not assume it was checked and found equal.
 *
 * Three fields diverge for reasons that are findings rather than noise. Each has its own test below
 * so the divergence is executable and named instead of hidden inside an exclusion list.
 */
final class GeneratedMatchesHandWrittenTest extends TestCase
{
    private const string HAND = 'Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\\';

    private const string GENERATED = 'Ardenexal\FHIRTools\Component\Models\\';

    /**
     * Membership and declaration order agree.
     *
     * Order is asserted rather than treated as an artifact: `Parameters.parameter` is ordered on the
     * wire and the repeated-entry convention for `max: '*'` makes that order meaningful, so a
     * generator that sorted required-first or alphabetically would be wrong here.
     */
    #[DataProvider('payloadPairProvider')]
    public function testParameterSequenceMatches(string $hand, string $generated): void
    {
        self::assertSame(
            array_keys(self::parameters($hand)),
            array_keys(self::parameters($generated)),
            'Generated and hand-written parameters differ in membership or declaration order.',
        );
    }

    /**
     * `use`, `min`, `max` and `type` agree on every parameter.
     *
     * These four are what the mapper reads to decide the slot, the cardinality and the wrapping, so
     * a divergence here is a behavioural divergence even where no current test exercises it.
     */
    #[DataProvider('payloadPairProvider')]
    public function testCardinalityAndTypeMatch(string $hand, string $generated): void
    {
        $generatedParameters = self::parameters($generated);

        foreach (self::parameters($hand) as $name => $expected) {
            $actual = $generatedParameters[$name];

            self::assertSame($expected->use, $actual->use, sprintf('"%s": use differs.', $name));
            self::assertSame($expected->min, $actual->min, sprintf('"%s": min differs.', $name));
            self::assertSame($expected->max, $actual->max, sprintf('"%s": max differs.', $name));
            self::assertSame($expected->type, $actual->type, sprintf('"%s": type differs.', $name));
            self::assertSame(
                $expected->searchType,
                $actual->searchType,
                sprintf('"%s": searchType differs — see N9, it is invariant-constrained.', $name),
            );
        }
    }

    /**
     * `partClass` points at an equivalent class on both sides, compared by what it declares.
     *
     * The FQCNs cannot be compared directly and that is not a defect: M01 nested part classes under
     * the payload's own namespace, M02 flattens them into the operation namespace with a `use`-
     * bearing prefix (`CodeSystemLookupOut` + `Designation`), which is what prevents N3's
     * `property` in/out collision structurally. So the target is resolved and its parameter
     * sequence compared, recursively — that is the property `partClass` exists to carry.
     */
    #[DataProvider('payloadPairProvider')]
    public function testPartClassTargetsAreEquivalent(string $hand, string $generated): void
    {
        $generatedParameters = self::parameters($generated);

        foreach (self::parameters($hand) as $name => $expected) {
            $actual = $generatedParameters[$name];

            if ($expected->partClass === null) {
                self::assertNull($actual->partClass, sprintf('"%s": generated grew a part class.', $name));

                continue;
            }

            self::assertNotNull($actual->partClass, sprintf('"%s": generated lost its part class.', $name));
            self::assertNotSame(
                $expected->partClass,
                $actual->partClass,
                sprintf('"%s": the two trees are expected to use different FQCNs.', $name),
            );

            self::assertSame(
                self::parameterTree($expected->partClass),
                self::parameterTree($actual->partClass),
                sprintf('"%s": the part classes declare different parameter trees.', $name),
            );
        }
    }

    /**
     * The variant sets agree — as sets, because the orders differ and the generator's is the right one.
     *
     * M01's fixtures took `AllowedTypeReader`'s alphabetical order (N2). M02 runs `VariantOrderer`,
     * which sorts by specialization depth descending with an alphabetical tie-break (N16/M1).
     * Asserting sequence equality here would pin the fixture ordering as correct, and N16 says it is
     * not: `resolveChoiceVariant` matches by `instanceof` in declaration order, so a supertype listed
     * before its subtype captures the subtype's values. Set equality plus
     * {@see self::testGeneratedVariantOrderNeverPutsASupertypeFirst} is the honest pair of claims.
     *
     * `propertyKind` is compared separately, in
     * {@see self::testBuiltinVariantsAreLabelledScalarLikeTheModelGenerator}.
     */
    #[DataProvider('payloadPairProvider')]
    public function testVariantSetsMatch(string $hand, string $generated): void
    {
        $generatedParameters = self::parameters($generated);

        foreach (self::parameters($hand) as $name => $expected) {
            $actual = $generatedParameters[$name];

            if ($expected->variants === null || $expected->variants === []) {
                self::assertTrue(
                    $actual->variants === null || $actual->variants === [],
                    sprintf('"%s": generated declares variants on a monomorphic parameter.', $name),
                );

                continue;
            }

            self::assertNotNull($actual->variants, sprintf('"%s": generated lost its variants.', $name));

            $expectedSet = self::variantSet($expected->variants);

            // Guards the comparison itself: an empty set on both sides would satisfy the equality
            // below while proving nothing, which is the failure mode N28 warns about.
            self::assertCount(count($expected->variants), $expectedSet);

            self::assertSame(
                $expectedSet,
                self::variantSet($actual->variants),
                sprintf('"%s": the variant sets differ in membership, jsonKey or phpType.', $name),
            );
        }
    }

    /**
     * No generated variant is listed after one of its own supertypes.
     *
     * The structural form of N16. Stated as the invariant rather than as an expected sequence,
     * because absolute order is a property of the loaded definition set and the FHIR version — R5
     * interposes `Base` above `Element`, so `Coding` scores a different depth there than in R4 and
     * legitimately lands in a different position (M4).
     *
     * This does **not** discriminate on `$lookup`: N16 records that `$lookup`'s seven types are safe
     * under alphabetical order too, by luck, and the hand-written fixtures satisfy it as well. The
     * discriminating case is `{uri, url}`, which no core `$lookup` exercises and which
     * `VariantOrdererTest` covers directly. Asserted here anyway so a regression in the generated
     * output is caught where the output lives.
     */
    #[DataProvider('payloadPairProvider')]
    public function testGeneratedVariantOrderNeverPutsASupertypeFirst(string $hand, string $generated): void
    {
        foreach (self::parameters($generated) as $name => $parameter) {
            $types = array_column($parameter->variants ?? [], 'phpType');

            foreach ($types as $earlier => $supertype) {
                foreach (array_slice($types, $earlier + 1) as $subtype) {
                    self::assertFalse(
                        is_subclass_of($subtype, $supertype),
                        sprintf(
                            '"%s": %s is listed after its supertype %s, so resolveChoiceVariant would '
                            . 'match the supertype first and emit the wrong value[x] key.',
                            $name,
                            $subtype,
                            $supertype,
                        ),
                    );
                }
            }
        }
    }

    /**
     * The holders agree on every invocation field the mapper and M03's routing will read.
     */
    #[DataProvider('holderPairProvider')]
    public function testHoldersDeclareTheSameOperation(string $hand, string $generated): void
    {
        $expected = self::holder($hand);
        $actual   = self::holder($generated);

        self::assertSame($expected->code, $actual->code);
        self::assertSame($expected->url, $actual->url);
        self::assertSame($expected->version, $actual->version);
        self::assertSame($expected->resource, $actual->resource);
        self::assertSame($expected->instance, $actual->instance);
        self::assertSame($expected->type, $actual->type);
        self::assertSame($expected->system, $actual->system);
        self::assertSame($expected->outputShape, $actual->outputShape);
        self::assertSame($expected->outputParameterName, $actual->outputParameterName);
    }

    /**
     * `outputClass` resolves to the same target on both sides, or to equivalent payload classes.
     *
     * Class B and C point at a `Models` resource, which both trees share verbatim. Class A points at
     * the generated Output, whose FQCN differs by design — compared by parameter tree, as with
     * `partClass`. Class D has none.
     */
    #[DataProvider('holderPairProvider')]
    public function testOutputClassTargetsAreEquivalent(string $hand, string $generated): void
    {
        $expected = self::holder($hand)->outputClass;
        $actual   = self::holder($generated)->outputClass;

        if ($expected === null) {
            self::assertNull($actual, 'A NoOutput holder grew an output class.');

            return;
        }

        self::assertNotNull($actual);

        if (str_starts_with($expected, self::GENERATED)) {
            // A shared Models resource — class B and C name the resource itself, so these must match.
            self::assertSame($expected, $actual);

            return;
        }

        self::assertSame(self::parameterTree($expected), self::parameterTree($actual));
    }

    /**
     * Every `phpName` agrees between hand-written and generated, including reserved words.
     *
     * This replaces a test that pinned a real divergence: the generator applied D3's reserved-word
     * guard to *parameter* identifiers as well as class names, emitting `$useParameter` for
     * `designation.use`. PHP reserves neither property nor variable names — M01's hand-written
     * `Designation` declares `public readonly ?Coding $use` and round-trips, which is the evidence
     * the escape was unnecessary. The guard now applies to class names only, where
     * `…\Designation\Use` genuinely is a fatal parse error.
     *
     * `use` is asserted explicitly below rather than left to the loop: it is the case that failed,
     * and a general assertion passing tells you less than the specific one passing.
     */
    #[DataProvider('payloadPairProvider')]
    public function testPhpNamesAgree(string $hand, string $generated): void
    {
        $generatedParameters = self::parameters($generated);

        foreach (self::parameters($hand) as $name => $expected) {
            self::assertSame(
                $expected->phpName,
                $generatedParameters[$name]->phpName,
                sprintf('"%s": phpName differs between hand-written and generated.', $name),
            );

            // The property must actually exist under that name, or an assertion reading it would
            // return null and pass while reading nothing — the failure mode N28 names.
            self::assertTrue(
                property_exists($generated, $generatedParameters[$name]->phpName),
                sprintf('phpName says "%s" but no such property exists.', $generatedParameters[$name]->phpName),
            );
        }
    }

    /**
     * Builtin-backed variants are labelled `scalar`, matching `FHIRModelGenerator` exactly.
     *
     * This replaces a test that pinned a divergence: the operation generator labelled `boolean`,
     * `integer` and `decimal` as `primitive` while the model generator called them `scalar`.
     *
     * Not cosmetic. `AbstractFHIRNormalizer::castNumericScalarForJson()` tests
     * `propertyKind === 'scalar'` to decide whether a numeric string is cast back to a number on the
     * way out, so a `decimal` labelled `primitive` would have stayed quoted in JSON while the
     * identical variant on a generated *model* emitted it unquoted. Two generators, one wire
     * convention, silently disagreeing — the M9 shape.
     *
     * Asserted against the hand-written fixtures rather than a literal, since those copied the model
     * generator's convention and are therefore the same authority.
     */
    public function testBuiltinVariantsAreLabelledScalarLikeTheModelGenerator(): void
    {
        $hand      = self::parameters(self::HAND . 'R4\CodeSystemLookupOutput\Property')['value'];
        $generated = self::parameters(self::GENERATED . 'R4\Operation\CodeSystemLookup\CodeSystemLookupOutProperty')['value'];

        $kindByType = static fn (?array $variants): array => array_column($variants ?? [], 'propertyKind', 'fhirType');

        foreach (['boolean', 'integer', 'decimal'] as $builtin) {
            self::assertSame(
                $kindByType($hand->variants)[$builtin],
                $kindByType($generated->variants)[$builtin],
                sprintf('"%s": propertyKind diverges from the model generator\'s convention.', $builtin),
            );
            self::assertSame('scalar', $kindByType($generated->variants)[$builtin]);
        }

        // Wrapper-backed and complex types keep their own kinds, so `scalar` is specific to the
        // three types the models really do back with a PHP scalar.
        self::assertSame('complex', $kindByType($generated->variants)['Coding']);
        self::assertSame('primitive', $kindByType($generated->variants)['code']);
    }

    /**
     * The generator carries R5's `parameter.scope`, which the hand-written fixtures dropped.
     *
     * A divergence in the generator's favour: `scope` is R5-only and 0..*, captured as metadata
     * rather than enforced (N6). M01 left it empty everywhere because nothing read it; the generator
     * transcribes it, so `$lookup`'s `system` and `version` IN parameters correctly report
     * `['type']`. Asserted so the richer output is a stated property rather than an unnoticed one.
     */
    public function testGeneratedCarriesR5ScopeTheFixturesOmitted(): void
    {
        $hand      = self::parameters(self::HAND . 'R5\CodeSystemLookupInput');
        $generated = self::parameters(self::GENERATED . 'R5\Operation\CodeSystemLookup\CodeSystemLookupInput');

        self::assertSame([], $hand['system']->scope);
        self::assertSame(['type'], $generated['system']->scope);
        self::assertSame(['type'], $generated['version']->scope);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function payloadPairProvider(): iterable
    {
        $stems = [
            'CodeSystemLookupInput'                        => 'CodeSystemLookup\CodeSystemLookupInput',
            'CodeSystemLookupOutput'                       => 'CodeSystemLookup\CodeSystemLookupOutput',
            'CodeSystemLookupOutput\Designation'           => 'CodeSystemLookup\CodeSystemLookupOutDesignation',
            'CodeSystemLookupOutput\Property'              => 'CodeSystemLookup\CodeSystemLookupOutProperty',
            'CodeSystemLookupOutput\PropertySubproperty'   => 'CodeSystemLookup\CodeSystemLookupOutPropertySubproperty',
            'ValueSetExpandInput'                          => 'ValueSetExpand\ValueSetExpandInput',
        ];

        foreach (['R4', 'R5'] as $version) {
            foreach ($stems as $handStem => $generatedStem) {
                yield sprintf('%s %s', $version, $handStem) => [
                    self::HAND . $version . '\\' . $handStem,
                    self::GENERATED . $version . '\Operation\\' . $generatedStem,
                ];
            }
        }

        // R4-only: M01 hand-wrote the class-C and class-D fixtures against R4 alone, and R4B was
        // never hand-written at all. Nothing is invented here to fill the gap.
        yield 'R4 MeasureSubmitDataInput' => [
            self::HAND . 'R4\MeasureSubmitDataInput',
            self::GENERATED . 'R4\Operation\MeasureSubmitData\MeasureSubmitDataInput',
        ];

        yield 'R4 ResourceGraphInput' => [
            self::HAND . 'R4\ResourceGraphInput',
            self::GENERATED . 'R4\Operation\ResourceGraph\ResourceGraphInput',
        ];
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function holderPairProvider(): iterable
    {
        foreach (['R4', 'R5'] as $version) {
            foreach (['CodeSystemLookup', 'ValueSetExpand'] as $stem) {
                yield sprintf('%s %sOperation', $version, $stem) => [
                    sprintf('%s%s\%sOperation', self::HAND, $version, $stem),
                    sprintf('%s%s\Operation\%s\%sOperation', self::GENERATED, $version, $stem, $stem),
                ];
            }
        }

        yield 'R4 MeasureSubmitDataOperation' => [
            self::HAND . 'R4\MeasureSubmitDataOperation',
            self::GENERATED . 'R4\Operation\MeasureSubmitData\MeasureSubmitDataOperation',
        ];

        yield 'R4 ResourceGraphOperation' => [
            self::HAND . 'R4\ResourceGraphOperation',
            self::GENERATED . 'R4\Operation\ResourceGraph\ResourceGraphOperation',
        ];
    }

    /**
     * Every `#[FhirOperationParameter]` on a class, keyed by wire name, in declaration order.
     *
     * Keyed by wire name rather than by property name because the wire name is the only identifier
     * both trees agree on — `phpName` is exactly what diverges. `array_keys()` on the result still
     * gives declaration order, so membership and order come from one read.
     *
     * @return array<string, FhirOperationParameter>
     */
    private static function parameters(string $class): array
    {
        self::assertTrue(class_exists($class), sprintf('Class %s does not exist.', $class));

        $parameters = [];

        foreach ((new \ReflectionClass($class))->getProperties() as $property) {
            foreach ($property->getAttributes(FhirOperationParameter::class) as $attribute) {
                $instance                    = $attribute->newInstance();
                $parameters[$instance->name] = $instance;
            }
        }

        self::assertNotSame([], $parameters, sprintf('%s declares no operation parameters.', $class));

        return $parameters;
    }

    /**
     * A class's parameter tree reduced to the facts both trees must agree on.
     *
     * Recurses through `partClass` so a nested class is compared by what it declares rather than by
     * its FQCN. Deliberately excludes `phpName` and `documentation` — see the class docblock.
     *
     * @return array<string, mixed>
     */
    private static function parameterTree(string $class): array
    {
        $tree = [];

        foreach (self::parameters($class) as $name => $parameter) {
            $tree[$name] = [
                'use'      => $parameter->use,
                'min'      => $parameter->min,
                'max'      => $parameter->max,
                'type'     => $parameter->type,
                'variants' => self::variantSet($parameter->variants),
                'part'     => $parameter->partClass === null ? null : self::parameterTree($parameter->partClass),
            ];
        }

        return $tree;
    }

    /**
     * The variant list as an order-insensitive set of its three load-bearing fields.
     *
     * `fhirType` names the type, `jsonKey` is what reaches the wire and `phpType` is what
     * `resolveChoiceVariant` matches against. `propertyKind` and `isBuiltin` are excluded — the
     * former diverges by convention (see the dedicated test) and the latter is derivable from
     * `phpType` via `PropertyVariantMetadata::fromArray()`.
     *
     * @param list<array{fhirType: string, propertyKind: string, phpType: string, jsonKey: string}>|null $variants
     *
     * @return list<string>
     */
    private static function variantSet(?array $variants): array
    {
        $set = array_map(
            static fn (array $variant): string => sprintf(
                '%s|%s|%s',
                $variant['fhirType'],
                $variant['jsonKey'],
                $variant['phpType'],
            ),
            $variants ?? [],
        );

        sort($set);

        return $set;
    }

    private static function holder(string $class): FhirOperation
    {
        self::assertTrue(class_exists($class), sprintf('Class %s does not exist.', $class));

        $attributes = (new \ReflectionClass($class))->getAttributes(FhirOperation::class);

        self::assertCount(1, $attributes, sprintf('%s carries no #[FhirOperation].', $class));

        return $attributes[0]->newInstance();
    }
}
