<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\BuilderContextTypeIndex;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\OutputShapeClassifier;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\TypeIndexInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Checks output-shape classification against M00's **pre-registered** counts.
 *
 * M00 measured the shape distribution across all three core packages before any generator existed
 * and wrote the numbers into `inventory.md`. Asserting against them here makes this test arithmetic
 * rather than a judgement made under sunk cost: if the classifier disagrees, one of the two is
 * wrong and it must be settled before ~150 classes are generated on the answer.
 *
 * The classification rule is keyed on the parameter **name**, not on cardinality — see
 * {@see OutputShapeClassifier}. `ValueSet/$expand` (a `ValueSet` named `return`) is bare;
 * `Resource/$graph` (a `Bundle` named `result`) is wrapped. Same cardinality, opposite wire shapes.
 *
 * Fixtures are committed projections of the published definitions, carrying `url`, `code`, `kind`
 * and every OUT parameter's `name`/`use`/`type` verbatim — which is the complete set of fields the
 * classifier reads. `demo/var/cache/dev/.fhir/` is gitignored and environment-dependent, so reading
 * it here would make the test non-hermetic.
 */
final class OutputShapeClassifierTest extends TestCase
{
    /**
     * The whole point of the milestone's pre-registered threshold, as one assertion per version.
     *
     * @param array<string, int> $expected
     */
    #[DataProvider('expectedDistributionProvider')]
    public function testDistributionMatchesTheInventoryMeasurement(string $version, array $expected): void
    {
        $classifier = new OutputShapeClassifier();
        $counts     = array_fill_keys(array_keys($expected), 0);
        $byShape    = [];

        foreach (self::operations($version) as $operation) {
            $shape = $classifier->classify($operation, self::types($version))['shape'];

            ++$counts[$shape];
            $byShape[$shape][] = $operation['code'];
        }

        foreach ($expected as $shape => $count) {
            self::assertSame(
                $count,
                $counts[$shape],
                sprintf(
                    "%s %s: inventory.md measured %d, classifier found %d.\nClassified as %s: %s",
                    $version,
                    $shape,
                    $count,
                    $counts[$shape],
                    $shape,
                    implode(', ', $byShape[$shape] ?? []),
                ),
            );
        }

        self::assertSame(
            array_sum($expected),
            array_sum($counts),
            'Every operation must land in exactly one shape — none skipped, none double-counted.',
        );
    }

    /**
     * @return iterable<string, array{string, array<string, int>}>
     */
    public static function expectedDistributionProvider(): iterable
    {
        // Verbatim from .goat-flow/plans/operation-codegen/inventory.md, measured 2026-08-06.
        yield 'R4' => ['r4', [
            OutputShapeClassifier::SHAPE_PARAMETERS          => 14,
            OutputShapeClassifier::SHAPE_BARE_RESOURCE       => 27,
            OutputShapeClassifier::SHAPE_NAMED_BARE_RESOURCE => 3,
            OutputShapeClassifier::SHAPE_NO_OUTPUT           => 3,
        ]];

        yield 'R4B' => ['r4b', [
            OutputShapeClassifier::SHAPE_PARAMETERS          => 14,
            OutputShapeClassifier::SHAPE_BARE_RESOURCE       => 27,
            OutputShapeClassifier::SHAPE_NAMED_BARE_RESOURCE => 3,
            OutputShapeClassifier::SHAPE_NO_OUTPUT           => 3,
        ]];

        yield 'R5' => ['r5', [
            OutputShapeClassifier::SHAPE_PARAMETERS          => 16,
            OutputShapeClassifier::SHAPE_BARE_RESOURCE       => 39,
            OutputShapeClassifier::SHAPE_NAMED_BARE_RESOURCE => 3,
            OutputShapeClassifier::SHAPE_NO_OUTPUT           => 2,
        ]];
    }

    /**
     * The two operations M01 proved by hand classify as M01 found them.
     *
     * `$expand` and `$graph` are the concrete instances behind the name-vs-cardinality rule, and
     * both were exercised end-to-end by `OperationOutputShapeTest`. If the classifier disagrees with
     * what the mapper was proven against, the generator would emit holders the mapper cannot serve.
     */
    public function testTheTwoShapesProvenInM01ClassifyAsProven(): void
    {
        $classifier = new OutputShapeClassifier();
        $operations = self::operations('r4');

        $expand = $classifier->classify($operations['http://hl7.org/fhir/OperationDefinition/ValueSet-expand'], self::types('r4'));
        self::assertSame(OutputShapeClassifier::SHAPE_BARE_RESOURCE, $expand['shape']);
        self::assertSame('ValueSet', $expand['outputType']);
        self::assertNull($expand['outputParameterName'], 'BareResource is `return` by definition.');

        $graph = $classifier->classify($operations['http://hl7.org/fhir/OperationDefinition/Resource-graph'], self::types('r4'));
        self::assertSame(OutputShapeClassifier::SHAPE_NAMED_BARE_RESOURCE, $graph['shape']);
        self::assertSame('Bundle', $graph['outputType']);
        self::assertSame('result', $graph['outputParameterName'], 'The wrapper name must be retained.');

        $lookup = $classifier->classify($operations['http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup'], self::types('r4'));
        self::assertSame(OutputShapeClassifier::SHAPE_PARAMETERS, $lookup['shape']);

        $submit = $classifier->classify($operations['http://hl7.org/fhir/OperationDefinition/Measure-submit-data'], self::types('r4'));
        self::assertSame(OutputShapeClassifier::SHAPE_NO_OUTPUT, $submit['shape']);
    }

    /**
     * The name is what decides between the two bare shapes — cardinality is identical.
     *
     * Stated as a controlled experiment rather than inferred from the corpus: the same sole
     * resource-typed OUT parameter classifies differently on its name alone.
     */
    public function testOnlyTheParameterNameSeparatesTheTwoBareShapes(): void
    {
        $classifier = new OutputShapeClassifier();

        $asReturn = $classifier->classify(['parameter' => [
            ['name' => 'return', 'use' => 'out', 'type' => 'Bundle'],
        ]], self::types('r4'));
        $asOther = $classifier->classify(['parameter' => [
            ['name' => 'outcome', 'use' => 'out', 'type' => 'Bundle'],
        ]], self::types('r4'));

        self::assertSame(OutputShapeClassifier::SHAPE_BARE_RESOURCE, $asReturn['shape']);
        self::assertSame(OutputShapeClassifier::SHAPE_NAMED_BARE_RESOURCE, $asOther['shape']);
        self::assertSame($asReturn['outputType'], $asOther['outputType'], 'Only the shape may differ.');
    }

    /**
     * A sole *primitive* output still needs the envelope — there is nothing to un-wrap to.
     *
     * The un-wrap rule requires a Resource. A lone `boolean` named `return` is not one, and
     * classifying it as bare would produce a response body that is not a FHIR resource at all.
     */
    public function testSolePrimitiveOutputIsStillParametersEvenWhenNamedReturn(): void
    {
        $shape = (new OutputShapeClassifier())->classify(['parameter' => [
            ['name' => 'return', 'use' => 'out', 'type' => 'boolean'],
        ]], self::types('r4'));

        self::assertSame(OutputShapeClassifier::SHAPE_PARAMETERS, $shape['shape']);
        self::assertNull($shape['outputType']);
    }

    /**
     * IN parameters never influence the output shape.
     *
     * `$submit-data` has two IN parameters and no OUT ones; a classifier that counted `parameter[]`
     * without filtering on `use` would call it Parameters-shaped.
     */
    public function testInputParametersAreIgnored(): void
    {
        $shape = (new OutputShapeClassifier())->classify(['parameter' => [
            ['name' => 'measureReport', 'use' => 'in', 'type' => 'MeasureReport'],
            ['name' => 'resource', 'use' => 'in', 'type' => 'Resource'],
        ]], self::types('r4'));

        self::assertSame(OutputShapeClassifier::SHAPE_NO_OUTPUT, $shape['shape']);
    }

    /**
     * A capitalised complex type is NOT treated as a resource — the regression this class was fixed for.
     *
     * The first implementation decided "resource" by capitalisation. `Meta` is capitalised and is a
     * `complex-type`, and R4's `$meta`, `$meta-add` and `$meta-delete` all declare `return:Meta`,
     * so exactly three operations per version were classified as bare-resource responses when they
     * actually answer with a `Parameters`. The pre-registered counts caught it.
     *
     * Asserted directly, rather than trusting the distribution test to notice, because the
     * distribution test only fails while the corpus happens to contain such an operation.
     */
    #[DataProvider('versionProvider')]
    public function testCapitalisedComplexTypeIsNotMistakenForAResource(string $version): void
    {
        $classifier = new OutputShapeClassifier();
        $types      = self::types($version);

        self::assertSame('complex-type', $types->kindOf('Meta'), 'Meta is no longer a complex type.');

        $shape = $classifier->classify(['parameter' => [
            ['name' => 'return', 'use' => 'out', 'type' => 'Meta'],
        ]], $types);

        self::assertSame(
            OutputShapeClassifier::SHAPE_PARAMETERS,
            $shape['shape'],
            'A complex type cannot be a response body on its own.',
        );

        // And a real resource under the same name still is bare — so the fix did not over-correct.
        self::assertSame(
            OutputShapeClassifier::SHAPE_BARE_RESOURCE,
            $classifier->classify(['parameter' => [
                ['name' => 'return', 'use' => 'out', 'type' => 'Bundle'],
            ]], $types)['shape'],
        );
    }

    /**
     * Every sole-OUT type across the whole corpus resolves in the type index, or is the `Any` wildcard.
     *
     * The classifier's answer is only as good as the index's coverage: a type it cannot resolve is
     * treated as a non-resource and silently becomes Parameters-shaped. This proves no core
     * operation depends on that fallback — the one unresolvable code is `Any`, which is a wildcard
     * rather than a StructureDefinition and is handled explicitly.
     */
    #[DataProvider('versionProvider')]
    public function testEverySoleOutputTypeResolvesInTheIndex(string $version): void
    {
        $types       = self::types($version);
        $unresolved  = [];

        foreach (self::operations($version) as $operation) {
            $outputs = array_values(array_filter(
                $operation['parameter'],
                static fn (array $p): bool => $p['use'] === 'out',
            ));

            if (count($outputs) !== 1 || !is_string($outputs[0]['type'] ?? null)) {
                continue;
            }

            $type = $outputs[0]['type'];

            if ($type !== 'Any' && $types->kindOf($type) === null) {
                $unresolved[$type] = true;
            }
        }

        self::assertSame(
            [],
            array_keys($unresolved),
            'A sole OUT parameter type is missing from the type index, so it silently falls back to '
            . 'the Parameters shape instead of being classified on evidence.',
        );
    }

    /**
     * `kind = 'query'` is present in R5 and must be excluded from generation, not classified.
     *
     * One R5 definition. Recorded here because the exclusion happens at the generator's call site,
     * and this is the assertion that the thing being excluded actually exists.
     */
    public function testR5CarriesExactlyOneQueryKindDefinition(): void
    {
        $queries = array_filter(
            self::rawOperations('r5'),
            static fn (array $o): bool => ($o['kind'] ?? null) === 'query',
        );

        self::assertCount(1, $queries, 'R5 no longer has exactly one kind=query definition.');
        self::assertSame([], array_filter(
            self::rawOperations('r4'),
            static fn (array $o): bool => ($o['kind'] ?? null) === 'query',
        ), 'R4 gained a kind=query definition.');
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4'  => ['r4'];
        yield 'R4B' => ['r4b'];
        yield 'R5'  => ['r5'];
    }

    /**
     * Generatable operations only — `kind = 'query'` is excluded by design (see M03).
     *
     * @return array<string, array<string, mixed>>
     */
    private static function operations(string $version): array
    {
        return array_filter(
            self::rawOperations($version),
            static fn (array $o): bool => ($o['kind'] ?? null) !== 'query',
        );
    }

    /**
     * A type index over the published StructureDefinitions for one version.
     *
     * Backed by a committed projection carrying `url`/`name`/`kind`/`derivation`/`baseDefinition`
     * verbatim for every non-profile type. In production this is `BuilderContextTypeIndex` over the
     * real loaded definitions; the same data, read the same way.
     */
    private static function types(string $version): TypeIndexInterface
    {
        $file = sprintf('%s/../../Fixtures/TypeIndex/%s-type-index.json', __DIR__, $version);

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Type index %s is unreadable.', $file));

        /** @var array<string, array<string, mixed>> $definitions */
        $definitions = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        $context = new BuilderContext();
        $context->loadDefinitions($definitions);

        return new BuilderContextTypeIndex($context);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function rawOperations(string $version): array
    {
        $file = sprintf('%s/../../Fixtures/OperationManifests/%s-operations.json', __DIR__, $version);

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Operation manifest %s is unreadable.', $file));

        /** @var array<string, array<string, mixed>> $operations */
        $operations = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        return $operations;
    }
}
