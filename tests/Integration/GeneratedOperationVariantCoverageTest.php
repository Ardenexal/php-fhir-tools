<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\AllowedTypeReader;

/**
 * M03's coverage gate: no polymorphic operation parameter silently degrades to unconstrained.
 *
 * A parameter typed `Element` (or `*`) carries no type information of its own — the definition
 * constrains it to a closed list of concrete types, and each becomes a `value[x]` variant on the wire.
 * If the generator fails to resolve that list, the emitted property is a bare `?string` with no
 * variants: it compiles, passes static analysis, serializes without error, and produces wire output
 * that names no type at all. There is nothing to notice. That is the failure this gate exists to make
 * impossible, and it is the reason the milestone plan was rewritten — the original gate ("≥1 R4B and
 * ≥1 R5 operation round-trips") would have passed green with the whole R5 path structurally wrong,
 * because the operation it happened to pick has no allowed-type information at all.
 *
 * ## Why this is a property, not a sample
 *
 * The population is **derived from the published definitions**, never listed here: every parameter of
 * every `kind = operation` definition, at every nesting depth, is walked, and the polymorphic ones are
 * whichever ones declare `type` of `Element` or `*`. Add an operation to a future package and it is
 * covered the moment the manifest is refreshed. A hand-listed population is precisely what this plan
 * has been bitten by three times (M02 notes M3 and M9: a curated complex-type list, then two curated
 * abstract-resource lists, each standing in for spec data and each wrong).
 *
 * ## Why the *expectations* are literals anyway
 *
 * {@see self::EXPECTED_VARIANTS} pins the exact type list for each polymorphic parameter. That is a
 * deliberate tripwire rather than an oversight: non-emptiness alone cannot see a **partial** drop, and
 * a partial drop is the more likely bug. `CodeSystem/$lookup`'s `property.value` resolving 7 types
 * down to 2 still passes "has variants" while having silently lost five wire representations. Pinning
 * the list makes any drift fail with the specific type that moved.
 *
 * The two halves fail in different directions on purpose. A newly polymorphic parameter appearing in
 * an updated package has no entry here and fails as *unexpected* — it is never skipped. A parameter
 * disappearing fails as *missing*. Neither can pass by default.
 *
 * ## What the manifest can and cannot answer
 *
 * The committed manifests do not carry `parameter.extension` or `parameter.allowedType`, so this gate
 * cannot re-derive allowed types from them. It does not need to: across R4, R4B and R5 core packages
 * the equivalence `type ∈ {Element, *}` ⟺ "bears allowed-type information" holds **set-exactly**
 * (verified against the packages 2026-08-07 — 6, 6 and 10 parameters, both sides identical). M01 note
 * N5 is the reason it is safe to rely on: the generator asserts rather than emitting an untyped
 * property, so a polymorphic parameter with no resolvable source is a hard failure at generation time,
 * not a silent empty set here.
 *
 * Reading the packages directly instead was rejected — `demo/var/cache/dev/.fhir/` is gitignored and
 * depends on which packages the developer last pulled, so the check would pass or fail for reasons
 * unrelated to the code (M01 note N4).
 *
 * @see GeneratedOperationsMatchPublishedDefinitionsTest for membership, order and cardinality fidelity
 * @see VariantOrderingMatchesModelInheritanceTest for the *order* of the variants this gate counts
 */
final class GeneratedOperationVariantCoverageTest extends TestCase
{
    /**
     * FHIR type codes meaning "this parameter is polymorphic".
     *
     * `*` is the OperationDefinition spelling of "any type"; `Element` is the closed-list spelling.
     * Both are resolved through the same allowed-type machinery.
     */
    private const array POLYMORPHIC_TYPES = ['Element', '*'];

    /**
     * The complete polymorphic population, keyed `version|operationCode|parameterPath|use`.
     *
     * Measured from the core packages 2026-08-07 and pre-registered before this test existed. Every
     * entry is sourced from the legacy `operationdefinition-allowed-type` extension: **no shipped core
     * definition populates R5's first-class `allowedType` element**, on any version, which is exactly
     * why {@see AllowedTypeReader} unions both
     * sources instead of branching on version.
     *
     * @var array<string, list<string>>
     */
    private const array EXPECTED_VARIANTS = [
        // --- R4 -------------------------------------------------------------------------------
        'R4|find-matches|property.value|in'                     => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R4|find-matches|property.subproperty.value|in'         => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R4|find-matches|match.unmatched.value|out'             => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R4|find-matches|match.unmatched.property.value|out'    => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R4|lookup|property.value|out'                          => ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'],
        'R4|lookup|property.subproperty.value|out'              => ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'],

        // --- R4B: byte-identical parameter model to R4, and asserted rather than assumed ---------
        'R4B|find-matches|property.value|in'                    => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R4B|find-matches|property.subproperty.value|in'        => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R4B|find-matches|match.unmatched.value|out'            => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R4B|find-matches|match.unmatched.property.value|out'   => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R4B|lookup|property.value|out'                         => ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'],
        'R4B|lookup|property.subproperty.value|out'             => ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'],

        // --- R5: adds ConceptMap-$translate, whose four parameters differ from one another -------
        // `match.dependsOn.value` is the widest in any version at nine types, and the only place
        // `id` and `uri` appear together — both specialize `string`/`uri` in the primitive hierarchy,
        // so it is also the strongest live exercise of the subclass-before-superclass variant order.
        'R5|find-matches|property.value|in'                     => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R5|find-matches|property.subproperty.value|in'         => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R5|find-matches|match.unmatched.value|out'             => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R5|find-matches|match.unmatched.property.value|out'    => ['Coding', 'boolean', 'code', 'dateTime', 'integer', 'string'],
        'R5|lookup|property.value|out'                          => ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'],
        'R5|lookup|property.subproperty.value|out'              => ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'],
        'R5|translate|dependency.value|in'                      => ['Coding', 'Quantity', 'boolean', 'code', 'string'],
        'R5|translate|match.property.value|out'                 => ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'],
        'R5|translate|match.product.value|out'                  => ['Coding', 'Quantity', 'boolean', 'code', 'string'],
        'R5|translate|match.dependsOn.value|out'                => ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'id', 'integer', 'string', 'uri'],
    ];

    /**
     * Population size per version, pre-registered independently of the map above.
     *
     * Redundant with `count(EXPECTED_VARIANTS)` by design: editing the map to make a failure go away
     * moves both the map and its own derived count together, and this literal is what does not move.
     *
     * @var array<string, int>
     */
    private const array PRE_REGISTERED_POPULATION = ['R4' => 6, 'R4B' => 6, 'R5' => 10];

    /**
     * @return \Generator<string, array{string}>
     */
    public static function versions(): \Generator
    {
        foreach (GeneratedOperationCorpus::VERSIONS as $version) {
            yield $version => [$version];
        }
    }

    /**
     * THE GATE: every polymorphic parameter resolves to its full published variant set.
     *
     * Walks the definitions rather than the generated tree, so a parameter the generator dropped
     * entirely fails here as a missing class or missing property, not as a silent absence.
     */
    #[DataProvider('versions')]
    public function testEveryPolymorphicParameterResolvesItsFullVariantSet(string $version): void
    {
        $seen = [];

        foreach ($this->polymorphicParameters($version) as $key => $parameter) {
            $attribute = $this->generatedParameter($version, $parameter);

            self::assertNotNull(
                $attribute->variants,
                sprintf(
                    '%s degraded to an unconstrained parameter: it is typed `%s` in the definition but '
                    . 'carries no variants, so the generated property names no FHIR type and its wire '
                    . 'output is untyped. This is the silent failure the gate exists to catch.',
                    $key,
                    $parameter['type'],
                ),
            );
            self::assertNotSame([], $attribute->variants, sprintf('%s resolved to an empty variant set.', $key));

            self::assertArrayHasKey(
                $key,
                self::EXPECTED_VARIANTS,
                sprintf(
                    '%s is polymorphic in the published definition but has no pre-registered variant '
                    . 'list. A package update introducing a polymorphic parameter must be reviewed, not '
                    . 'silently accepted — add its expected types after confirming them against the '
                    . 'definition.',
                    $key,
                ),
            );

            $actual = array_map(
                static fn (array $variant): string => $variant['fhirType'],
                $attribute->variants,
            );

            sort($actual);
            $expected = self::EXPECTED_VARIANTS[$key];
            sort($expected);

            self::assertSame(
                $expected,
                $actual,
                sprintf(
                    '%s resolved %d of %d published types. A partial resolution still passes a '
                    . '"has variants" check while having silently lost wire representations.',
                    $key,
                    count($actual),
                    count($expected),
                ),
            );

            $seen[] = $key;
        }

        self::assertCount(
            self::PRE_REGISTERED_POPULATION[$version],
            $seen,
            sprintf(
                'Expected %d polymorphic parameters in %s. A change here means the packages moved; '
                . 'confirm against the definitions before adjusting the literal.',
                self::PRE_REGISTERED_POPULATION[$version],
                $version,
            ),
        );
    }

    /**
     * The inverse: a monomorphic parameter must NOT carry variants.
     *
     * Guards over-application. A generator that attached the variant machinery to every parameter
     * would pass the gate above completely while emitting a `value[x]` choice for parameters the spec
     * gives exactly one type — invalid FHIR that round-trips symmetrically and so survives an
     * identity check.
     */
    #[DataProvider('versions')]
    public function testNoMonomorphicParameterCarriesVariants(string $version): void
    {
        $offenders = [];

        foreach (array_keys(GeneratedOperationCorpus::payloadClasses($version)) as $class) {
            foreach ($this->parameterAttributes($class) as $attribute) {
                $isPolymorphic = $attribute->type !== null
                    && in_array($attribute->type, self::POLYMORPHIC_TYPES, true);

                if (!$isPolymorphic && $attribute->variants !== null) {
                    $offenders[] = sprintf(
                        '%s::$%s (type `%s`)',
                        GeneratedOperationCorpus::shortName($class),
                        $attribute->phpName,
                        $attribute->type ?? 'null',
                    );
                }
            }
        }

        self::assertSame([], $offenders, sprintf(
            'Parameters with a concrete type carry a variant set in %s: %s. Only `Element` and `*` are '
            . 'polymorphic; anything else has exactly one wire representation.',
            $version,
            implode(', ', $offenders),
        ));
    }

    /**
     * Variants are declared subclass-before-superclass on the *generated operation classes*.
     *
     * Split out from the assertion above, which compares sorted lists and so is deliberately blind to
     * order. Order is not cosmetic here: `AbstractFHIRNormalizer::resolveChoiceVariant` matches a value
     * by `instanceof` in **declaration order**, and the FHIR primitives form real PHP inheritance
     * chains. `CodePrimitive extends StringPrimitive`, so a variant list declaring `string` before
     * `code` makes every `CodePrimitive` emit as `valueString` — wire output that is wrong, valid JSON,
     * and round-trips symmetrically. That is footgun `choice-variant-ordering.md` and M01 note N16.
     *
     * M02 closed this for the `VariantOrderer` unit and for the Models primitives, but nothing asserted
     * it on emitted operation variants — `GeneratedMatchesHandWrittenTest` compares variant *sets*, and
     * M02's own task note said "no core `$lookup` exercises it". That was measured before the 22
     * parameters this file enumerates. It is exercised, in every version: `lookup|property.value`
     * carries `code` and `string` together, and R5's `match.dependsOn.value` adds `id` and `uri`.
     *
     * The hierarchy is read from the committed type index via `baseDefinition`, the same source
     * `VariantOrderer` uses — never a hand-maintained table of pairs, which is the failure mode M02
     * notes M3 and M9 both record.
     */
    #[DataProvider('versions')]
    public function testVariantsAreDeclaredSubclassBeforeSuperclass(string $version): void
    {
        $context    = GeneratedOperationCorpus::typeIndexContext($version);
        $violations = [];
        $checked    = 0;

        foreach ($this->polymorphicParameters($version) as $key => $parameter) {
            $variants = $this->generatedParameter($version, $parameter)->variants ?? [];
            $types    = array_values(array_map(
                static fn (array $variant): string => $variant['fhirType'],
                $variants,
            ));

            foreach ($types as $earlier => $supertype) {
                foreach (array_slice($types, $earlier + 1) as $subtype) {
                    ++$checked;

                    if (in_array($supertype, $this->ancestorsOf($subtype, $context), true)) {
                        $violations[] = sprintf(
                            '%s declares `%s` before `%s`, but %s specializes %s — every %s value would '
                            . 'match the supertype first and emit as value%s.',
                            $key,
                            $supertype,
                            $subtype,
                            $subtype,
                            $supertype,
                            $subtype,
                            ucfirst($supertype),
                        );
                    }
                }
            }
        }

        self::assertSame([], $violations, implode("\n", $violations));
        self::assertGreaterThan(0, $checked, sprintf(
            'No variant pairs compared in %s — the walk found nothing and this assertion is vacuous.',
            $version,
        ));
    }

    /**
     * The gate must not be able to pass vacuously.
     *
     * Two ways it could: the walk never recurses (every polymorphic parameter in all three versions is
     * nested at least one level, and two are nested two levels), or the population is empty. Both would
     * report green. M02 note N28 is the general form — an assertion that passes when the behaviour is
     * right *and* when the code never ran is not proof.
     */
    #[DataProvider('versions')]
    public function testTheCorpusActuallyContainsTheShapesThisGateGuards(string $version): void
    {
        $depths = [];

        foreach (array_keys($this->polymorphicParameters($version)) as $key) {
            $path     = explode('|', $key)[2];
            $depths[] = substr_count($path, '.') + 1;
        }

        self::assertNotSame([], $depths, sprintf(
            'No polymorphic parameters found in %s. Either the manifest is empty or the walk is broken; '
            . 'every other assertion in this file would report green.',
            $version,
        ));
        self::assertGreaterThanOrEqual(2, max($depths), sprintf(
            'No polymorphic parameter deeper than one level in %s, so the recursive descent is never '
            . 'exercised and a walk that ignored `part[]` entirely would still pass.',
            $version,
        ));
    }

    /**
     * Every type a code specializes, walking `baseDefinition` to the root.
     *
     * Only `derivation === 'specialization'` extends the hierarchy — a `constraint` derivation is a
     * profile of the same type and adds no PHP subclass, so following it would invent an ancestor.
     * Mirrors `VariantOrderer::depthOf()` deliberately: both read the same chain from the same source,
     * so this check cannot drift from the production ordering it verifies.
     *
     * @return list<string>
     */
    private function ancestorsOf(string $typeCode, BuilderContext $context): array
    {
        $ancestors = [];
        $url       = 'http://hl7.org/fhir/StructureDefinition/' . $typeCode;
        $seen      = [];

        // Bounded like VariantOrderer's own walk: a malformed package could carry a cyclic chain.
        while (count($ancestors) < 20 && !isset($seen[$url])) {
            $seen[$url]  = true;
            $definition  = $context->getDefinition($url);
            $base        = $definition['baseDefinition'] ?? null;

            if (!is_string($base) || ($definition['derivation'] ?? null) !== 'specialization') {
                break;
            }

            $ancestors[] = substr($base, (int) strrpos($base, '/') + 1);
            $url         = $base;
        }

        return $ancestors;
    }

    /**
     * Every polymorphic parameter in a version, keyed `version|operationCode|path|use`.
     *
     * `path` is the parameter's own dotted path and `use` is part of the key because neither alone is
     * unique: `$lookup` declares `property` twice at the top level, once `in` and once `out` (M01 note
     * N3). Addressing by name would silently resolve to whichever came first.
     *
     * @return array<string, array{operationUrl: string, parentPath: string, name: string, use: string, type: string}>
     */
    private function polymorphicParameters(string $version): array
    {
        $found = [];

        foreach (GeneratedOperationCorpus::operations($version) as $url => $definition) {
            $code = is_string($definition['code'] ?? null) ? $definition['code'] : basename($url);

            $walk = static function(array $parameters, string $prefix) use (&$walk, &$found, $version, $url, $code): void {
                foreach ($parameters as $parameter) {
                    if (!is_array($parameter) || !is_string($parameter['name'] ?? null)) {
                        continue;
                    }

                    $name = $parameter['name'];
                    $path = $prefix === '' ? $name : $prefix . '.' . $name;
                    $type = $parameter['type'] ?? null;
                    $use  = is_string($parameter['use'] ?? null) ? $parameter['use'] : '';

                    if (is_string($type) && in_array($type, self::POLYMORPHIC_TYPES, true)) {
                        $found[sprintf('%s|%s|%s|%s', $version, $code, $path, $use)] = [
                            'operationUrl' => $url,
                            'parentPath'   => $prefix,
                            'name'         => $name,
                            'use'          => $use,
                            'type'         => $type,
                        ];
                    }

                    $walk(GeneratedOperationCorpus::parts($parameter), $path);
                }
            };

            $walk(GeneratedOperationCorpus::parts(['part' => $definition['parameter'] ?? []]), '');
        }

        return $found;
    }

    /**
     * Resolve a published parameter to the attribute on the generated class that carries it.
     *
     * The parameter's **parent** path selects the payload class — a top-level parameter lives on the
     * Input/Output class, which the generator marks with `path: ''` — and the parameter's own name
     * selects the constructor argument within it.
     *
     * @param array{operationUrl: string, parentPath: string, name: string, use: string, type: string} $parameter
     */
    private function generatedParameter(string $version, array $parameter): FhirOperationParameter
    {
        $candidates = array_filter(
            GeneratedOperationCorpus::payloadClassesFor($version, $parameter['operationUrl']),
            static fn (FhirOperationPayload $payload): bool => $payload->use === $parameter['use']
                && $payload->path                                            === $parameter['parentPath'],
        );

        self::assertCount(1, $candidates, sprintf(
            'Expected exactly one generated %s payload class at path "%s" for %s, found %d. Zero means '
            . 'the parameter group was never emitted; more than one means two paths collided.',
            $parameter['use'],
            $parameter['parentPath'],
            $parameter['operationUrl'],
            count($candidates),
        ));

        $class = array_key_first($candidates);
        self::assertIsString($class);

        foreach ($this->parameterAttributes($class) as $attribute) {
            if ($attribute->name === $parameter['name']) {
                return $attribute;
            }
        }

        self::fail(sprintf(
            '%s declares no parameter named "%s", though the published definition does. The wire name '
            . 'is matched verbatim — it is never normalised, so a mismatch here is a dropped parameter, '
            . 'not a naming difference.',
            $class,
            $parameter['name'],
        ));
    }

    /**
     * Every `#[FhirOperationParameter]` on a generated payload class, in declaration order.
     *
     * @param class-string $class
     *
     * @return list<FhirOperationParameter>
     */
    private function parameterAttributes(string $class): array
    {
        $constructor = (new \ReflectionClass($class))->getConstructor();

        if ($constructor === null) {
            return [];
        }

        $attributes = [];

        foreach ($constructor->getParameters() as $parameter) {
            foreach ($parameter->getAttributes(FhirOperationParameter::class) as $marker) {
                $attributes[] = $marker->newInstance();
            }
        }

        return $attributes;
    }
}
