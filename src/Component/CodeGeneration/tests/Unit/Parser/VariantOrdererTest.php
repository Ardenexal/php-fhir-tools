<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContextInterface;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\AllowedTypeReader;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\VariantOrderer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Proves choice variants are ordered subtype-first, from specification data rather than a table.
 *
 * `AbstractFHIRNormalizer::resolveChoiceVariant` matches by `instanceof` in **declaration order**, so
 * a supertype listed before its subtype captures the subtype's values and emits the wrong `value[x]`
 * key — silently, producing structurally valid but wrong FHIR. See
 * `.goat-flow/learning-loop/footguns/choice-variant-ordering.md`.
 *
 * The specific trap is alphabetical ordering, which looks like safe canonicalisation and happens to
 * be correct for most pairs (`code` < `string`, `canonical` < `uri`) — which is exactly why it
 * survives casual testing. `testTheAlphabeticallyUnsafePairIsOrderedCorrectly` is the assertion the
 * whole class exists for; every other test here guards a way of accidentally reintroducing the sort.
 */
final class VariantOrdererTest extends TestCase
{
    /**
     * The case alphabetical sorting gets wrong: `{uri, url}`.
     *
     * `UrlPrimitive extends UriPrimitive`, so `uri` first would claim every `UrlPrimitive`. No core
     * `$lookup` parameter exercises this pair, which is why it needs asserting directly rather than
     * being left to a round-trip test to notice.
     */
    #[DataProvider('versionProvider')]
    public function testTheAlphabeticallyUnsafePairIsOrderedCorrectly(string $version): void
    {
        $ordered = (new VariantOrderer())->order(['uri', 'url'], self::context($version));

        self::assertSame(['url', 'uri'], $ordered, 'A supertype was emitted before its subtype.');
        self::assertNotSame(['uri', 'url'], $ordered, 'The list came back in alphabetical order.');
    }

    /**
     * Every real subtype/supertype pair in the primitive hierarchy, asserted individually.
     *
     * @param list<string> $pair
     */
    #[DataProvider('subtypePairProvider')]
    public function testSubtypeAlwaysPrecedesItsSupertype(string $version, array $pair, string $subtype): void
    {
        $ordered = (new VariantOrderer())->order($pair, self::context($version));

        self::assertSame(
            $subtype,
            $ordered[0],
            sprintf('Expected "%s" first in [%s].', $subtype, implode(', ', $ordered)),
        );
    }

    /**
     * @return iterable<string, array{string, list<string>, string}>
     */
    public static function subtypePairProvider(): iterable
    {
        $pairs = [
            ['code', 'string'],
            ['id', 'string'],
            ['markdown', 'string'],
            ['url', 'uri'],
            ['canonical', 'uri'],
            ['oid', 'uri'],
            ['uuid', 'uri'],
            ['positiveInt', 'integer'],
            ['unsignedInt', 'integer'],
        ];

        foreach (['R4', 'R5'] as $version) {
            foreach ($pairs as [$subtype, $supertype]) {
                // Fed in *supertype-first* deliberately: passing the already-correct order would
                // pass even if order() were the identity function.
                yield sprintf('%s %s before %s', $version, $subtype, $supertype) => [
                    $version,
                    [$supertype, $subtype],
                    $subtype,
                ];
            }
        }
    }

    /**
     * Incomparable siblings are ordered alphabetically, so regeneration is byte-deterministic.
     *
     * `url` and `canonical` both derive from `uri` and neither derives from the other, so nothing in
     * the hierarchy decides between them. Determinism is an M02 exit criterion — two consecutive
     * regens must produce identical trees — so the tie-break cannot be left to sort stability.
     */
    #[DataProvider('versionProvider')]
    public function testIncomparableSiblingsGetADeterministicTieBreak(string $version): void
    {
        $orderer = new VariantOrderer();
        $context = self::context($version);

        $fromOneOrder     = $orderer->order(['url', 'canonical', 'oid', 'uri'], $context);
        $fromAnotherOrder = $orderer->order(['uri', 'oid', 'canonical', 'url'], $context);

        self::assertSame($fromOneOrder, $fromAnotherOrder, 'Ordering depends on input order.');
        self::assertSame(['canonical', 'oid', 'url', 'uri'], $fromOneOrder);
        self::assertSame('uri', end($fromOneOrder), 'The supertype must come last.');
    }

    /**
     * The real seven-type `$lookup` set keeps every subtype ahead of its supertype, losing nothing.
     */
    #[DataProvider('versionProvider')]
    public function testDepthOrderingHoldsAcrossTheWholeSevenTypeLookupSet(string $version): void
    {
        // The real `$lookup` `property.value` variant set, as AllowedTypeReader returns it (sorted).
        $lookupTypes = ['Coding', 'boolean', 'code', 'dateTime', 'decimal', 'integer', 'string'];

        $ordered = (new VariantOrderer())->order($lookupTypes, self::context($version));

        // Membership as a *set* — comparing sequences here would assert the input order, which is
        // the one thing this class deliberately changes.
        self::assertEqualsCanonicalizing($lookupTypes, $ordered, 'A type was lost or duplicated.');

        self::assertLessThan(
            array_search('string', $ordered, true),
            array_search('code', $ordered, true),
            '`code` must precede `string` — CodePrimitive extends StringPrimitive.',
        );

        // `Coding` is a complex type: unrelated to every primitive here, so its exact position is
        // not meaningful — only that it never precedes something that derives from it. Nothing in
        // the models subclasses `Coding`, so any position is safe.
        self::assertContains('Coding', $ordered);
    }

    /**
     * Depth is read from `baseDefinition`, and only for `specialization` derivations.
     *
     * Asserted **relatively**, never as absolute numbers. Depth counts hops until the chain stops
     * resolving, and how far that goes is a property of the loaded definition set and the FHIR
     * version — R5 interposes `Base` above `Element`, so every R5 type scores higher than its R4
     * counterpart. Ordering depends only on subtypes outranking their supertypes, so that is what
     * this asserts; pinning absolute values would make the test fail on a version bump that changed
     * nothing about correctness.
     */
    #[DataProvider('versionProvider')]
    public function testDepthReflectsTheSpecializationChain(string $version): void
    {
        $orderer = new VariantOrderer();
        $context = self::context($version);

        foreach ([['code', 'string'], ['url', 'uri'], ['positiveInt', 'integer']] as [$subtype, $supertype]) {
            self::assertGreaterThan(
                $orderer->depthOf($supertype, $context),
                $orderer->depthOf($subtype, $context),
                sprintf('"%s" must outrank its supertype "%s".', $subtype, $supertype),
            );
        }

        // Siblings sharing a parent land at the same depth — which is why the tie-break exists.
        self::assertSame(
            $orderer->depthOf('url', $context),
            $orderer->depthOf('canonical', $context),
            'Types sharing a parent must rank equally.',
        );

        // A code with no StructureDefinition at all bottoms out rather than throwing.
        self::assertSame(0, $orderer->depthOf('NotAType', $context));
    }

    /**
     * An unresolvable type sorts last rather than being dropped or crashing.
     *
     * Sorting it last is the safe direction: a type the context cannot resolve is never treated as
     * more specific than one it can, so it cannot steal a match from a known subtype. Dropping it
     * would be the dangerous alternative — a silently missing variant means a silently dropped value.
     */
    #[DataProvider('versionProvider')]
    public function testUnknownTypeIsRetainedAndSortsLast(string $version): void
    {
        $orderer = new VariantOrderer();
        $context = self::context($version);

        $ordered = $orderer->order(['SomeIGDefinedType', 'code', 'string'], $context);

        self::assertContains('SomeIGDefinedType', $ordered, 'An unresolvable type was silently dropped.');
        self::assertSame(0, $orderer->depthOf('SomeIGDefinedType', $context));
        self::assertSame('code', $ordered[0]);
    }

    /**
     * Duplicates collapse, so a union of both allowed-type sources cannot emit a variant twice.
     */
    #[DataProvider('versionProvider')]
    public function testDuplicatesAreCollapsed(string $version): void
    {
        $ordered = (new VariantOrderer())->order(['code', 'string', 'code'], self::context($version));

        self::assertSame(['code', 'string'], $ordered);
    }

    /**
     * The ordering consumes `AllowedTypeReader`'s output directly — the two must compose.
     *
     * The reader sorts alphabetically on purpose (so R4 and R5 compare equal); this asserts that
     * sorted output is re-ordered rather than passed through, which is the exact mistake the
     * footgun warns against.
     */
    #[DataProvider('versionProvider')]
    public function testReaderOutputIsReorderedNotPassedThrough(string $version): void
    {
        $parameter = [
            'name'      => 'value',
            'type'      => 'Element',
            'extension' => array_map(
                static fn (string $t): array => [
                    'url'      => 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type',
                    'valueUri' => $t,
                ],
                ['uri', 'url'],
            ),
        ];

        $types = (new AllowedTypeReader())->read($parameter);

        self::assertSame(['uri', 'url'], $types, 'Reader no longer returns a sorted list — re-check this test.');
        self::assertSame(['url', 'uri'], (new VariantOrderer())->order($types, self::context($version)));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4' => ['R4'];
        yield 'R5' => ['R5'];
    }

    /**
     * A context loaded with the published primitive StructureDefinitions.
     *
     * Fixtures are committed rather than read from `demo/var/cache/dev/.fhir/`, which is gitignored
     * and environment-dependent. Each entry is the verbatim `url`/`kind`/`derivation`/
     * `baseDefinition` subset of the published definition;
     * `tests/Integration/VariantOrderingMatchesModelInheritanceTest.php` checks the derived ordering
     * against the real generated class hierarchy, so a stale fixture cannot pass unnoticed.
     */
    private static function context(string $version): BuilderContextInterface
    {
        $file = sprintf('%s/../../Fixtures/TypeIndex/%s-type-index.json', __DIR__, strtolower($version));

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Type index %s is unreadable.', $file));

        /** @var array<string, array<string, mixed>> $definitions */
        $definitions = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        $context = new BuilderContext();
        $context->loadDefinitions($definitions);

        return $context;
    }
}
