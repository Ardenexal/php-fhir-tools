<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\OutputShapeClassifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Counts the generated operation tree against the parameter paths the published definitions declare.
 *
 * One arithmetic identity catches the whole silent-drop and silent-dedup family at once. Generating
 * 154 operations means transcribing ~830 parameters and every nested `part[]` group; the failure mode
 * is not a crash but a class that quietly never gets emitted, or two colliding paths resolving to one
 * class that looks complete. Neither is visible in output that compiles and passes static analysis.
 *
 * Per operation the tree must hold exactly: one holder, one Input when the definition has any
 * `use: in` parameter, one Output **only** when the response shape is `parameters` (the three bare
 * shapes answer with a resource and have no payload class by design), and one nested class per
 * `part[]` group at any depth.
 *
 * What fails this test: a dropped parameter group, a collision silently deduplicated, an operation
 * skipped entirely, R5's `kind = query` definition leaking into the tree, a stale class surviving a
 * clear-phase that does not cover `Operation/`, an Output emitted for a bare-resource response, and a
 * generated file whose namespace does not nest with its directory (it would not autoload).
 *
 * @see GeneratedOperationsMatchPublishedDefinitionsTest for what is *inside* each of these classes
 */
final class GeneratedOperationClassCountMatchesParameterPathsTest extends TestCase
{
    /**
     * Pre-registered in `M02-generator-all-versions.md` before this test existed, and reached
     * independently from the manifests.
     *
     * Held as literals rather than recomputed. Per-operation equality alone cannot see the output-shape
     * classifier drift, because the generator and this test both consult it and would move together;
     * the split of payload classes is the number that breaks when it does. `inventory.md` caught a real
     * classifier bug this way once already (`Meta` is capitalised but is a complex-type, so three
     * operations per version were misclassified) — a hand-written guard test missed it and only the
     * pre-registered count did.
     *
     * @var array<string, array{holders: int, payloads: int, nested: int, total: int}>
     */
    private const array PRE_REGISTERED = [
        'R4'  => ['holders' => 47, 'payloads' => 57, 'nested' => 11, 'total' => 115],
        'R4B' => ['holders' => 47, 'payloads' => 57, 'nested' => 11, 'total' => 115],
        'R5'  => ['holders' => 60, 'payloads' => 72, 'nested' => 13, 'total' => 145],
    ];

    /**
     * For every operation, generated classes == parameter paths requiring one.
     */
    #[DataProvider('versionProvider')]
    public function testEveryOperationEmitsOneClassPerParameterPath(string $version): void
    {
        $operations = GeneratedOperationCorpus::operations($version);
        $holders    = GeneratedOperationCorpus::holderClasses($version);

        foreach ($operations as $url => $definition) {
            $holderCount = count(array_filter(
                $holders,
                static fn (FhirOperation $holder): bool => $holder->url === $url,
            ));

            $actual = $holderCount + count(GeneratedOperationCorpus::payloadClassesFor($version, $url));

            self::assertSame(
                self::expectedClassCount($version, $definition),
                $actual,
                sprintf('%s %s emits the wrong number of classes.', $version, $url),
            );
        }

        self::assertCount(
            self::PRE_REGISTERED[$version]['holders'],
            $operations,
            sprintf('Fewer %s operations were checked than the manifest publishes.', $version),
        );
    }

    /**
     * The per-version totals, split into holders, top-level payloads and nested part classes.
     */
    #[DataProvider('versionProvider')]
    public function testTotalsMatchThePreRegisteredCounts(string $version): void
    {
        $expected = self::PRE_REGISTERED[$version];
        $payloads = GeneratedOperationCorpus::payloadClasses($version);

        $nested = array_filter(
            $payloads,
            static fn (FhirOperationPayload $payload): bool => $payload->path !== '',
        );

        self::assertCount($expected['holders'], GeneratedOperationCorpus::holderClasses($version));
        self::assertCount($expected['nested'], $nested, sprintf('%s nested part classes', $version));
        self::assertCount(
            $expected['payloads'],
            array_diff_key($payloads, $nested),
            sprintf('%s top-level Input/Output classes', $version),
        );
        self::assertSame(
            $expected['total'],
            count($payloads) + count(GeneratedOperationCorpus::holderClasses($version)),
            sprintf('%s total generated operation classes', $version),
        );
    }

    /**
     * The generated operations are exactly the published `kind = operation` set — no more, no fewer.
     *
     * Per-operation counting is blind to a whole operation going missing, and blind to one appearing
     * that should not exist. R5's single `kind = query` definition is the concrete case for the second
     * half: it is excluded by design, and nothing else in the suite would notice it being emitted.
     */
    #[DataProvider('versionProvider')]
    public function testGeneratedOperationsAreExactlyThePublishedOperationSet(string $version): void
    {
        $published = array_keys(GeneratedOperationCorpus::operations($version));

        $generated = array_values(array_map(
            static fn (FhirOperation $holder): string => $holder->url,
            GeneratedOperationCorpus::holderClasses($version),
        ));

        sort($published);
        sort($generated);

        self::assertSame($published, $generated, sprintf('%s generated operations differ from the manifest.', $version));

        $excluded = array_diff_key(
            GeneratedOperationCorpus::manifest($version),
            GeneratedOperationCorpus::operations($version),
        );

        foreach (array_keys($excluded) as $url) {
            self::assertNotContains(
                $url,
                $generated,
                sprintf('%s is not kind=operation and must not be generated.', $url),
            );
        }
    }

    /**
     * Every payload class on disk is reachable from a holder, and every reachable class is on disk.
     *
     * The two directions catch opposite defects. Globbing alone accepts an orphan no holder points at —
     * a stale class surviving regeneration, which looks identical to a live one. Walking alone accepts
     * a class that exists on disk but is wired to nothing, which is how a dropped `partClass` presents:
     * the mapper cannot reach the nested type and silently maps nothing there.
     */
    #[DataProvider('versionProvider')]
    public function testEveryPayloadClassIsReachableFromItsHolder(string $version): void
    {
        $payloads  = GeneratedOperationCorpus::payloadClasses($version);
        $reachable = [];

        foreach (GeneratedOperationCorpus::holderClasses($version) as $holderClass => $holder) {
            foreach ([$holder->inputClass, $holder->outputClass] as $entryPoint) {
                // A bare-resource holder points `outputClass` at a model resource, and an operation
                // with no IN parameters carries ''. Neither is a payload, and neither is an error.
                if ($entryPoint === null || !isset($payloads[$entryPoint]) || !class_exists($entryPoint)) {
                    continue;
                }

                self::assertSame(
                    $holder->url,
                    $payloads[$entryPoint]->operationUrl,
                    sprintf('%s points at a payload belonging to another operation.', $holderClass),
                );

                self::collectReachable($entryPoint, $payloads, $reachable);
            }
        }

        ksort($reachable);
        $discovered = $payloads;
        ksort($discovered);

        self::assertSame(
            array_keys($discovered),
            array_keys($reachable),
            sprintf('%s payload classes on disk and payload classes reachable from a holder differ.', $version),
        );
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function versionProvider(): iterable
    {
        foreach (GeneratedOperationCorpus::VERSIONS as $version) {
            yield $version => [$version];
        }
    }

    /**
     * Classes a definition must produce: the holder, one payload per populated direction, one per
     * nested `part[]` group.
     *
     * @param array<string, mixed> $definition
     */
    private static function expectedClassCount(string $version, array $definition): int
    {
        $expected = 1;

        $inputs = GeneratedOperationCorpus::parameters($definition, 'in');

        if ($inputs !== []) {
            $expected += 1 + self::countPartGroups($inputs);
        }

        // The three bare shapes return the resource itself, so no Output class exists to count. Keying
        // on the shape rather than on "has OUT parameters" is the whole point: 30 of 47 R4 operations
        // have OUT parameters and no Output class.
        if (GeneratedOperationCorpus::outputShape($version, $definition) === OutputShapeClassifier::SHAPE_PARAMETERS) {
            $expected += 1 + self::countPartGroups(GeneratedOperationCorpus::parameters($definition, 'out'));
        }

        return $expected;
    }

    /**
     * @param list<array<string, mixed>> $parameters
     */
    private static function countPartGroups(array $parameters): int
    {
        $count = 0;

        foreach ($parameters as $parameter) {
            $parts = GeneratedOperationCorpus::parts($parameter);

            if ($parts === []) {
                continue;
            }

            $count += 1 + self::countPartGroups($parts);
        }

        return $count;
    }

    /**
     * @param class-string                              $class
     * @param array<class-string, FhirOperationPayload> $payloads
     * @param array<class-string, true>                 $reachable
     */
    private static function collectReachable(string $class, array $payloads, array &$reachable): void
    {
        if (isset($reachable[$class])) {
            return;
        }

        $reachable[$class] = true;

        foreach ((new \ReflectionClass($class))->getConstructor()?->getParameters() ?? [] as $parameter) {
            foreach ($parameter->getAttributes(FhirOperationParameter::class) as $attribute) {
                $partClass = $attribute->newInstance()->partClass;

                if ($partClass === null) {
                    continue;
                }

                self::assertArrayHasKey(
                    $partClass,
                    $payloads,
                    sprintf('%s::$partClass points at %s, which is not a generated payload.', $class, $partClass),
                );

                self::collectReachable($partClass, $payloads, $reachable);
            }
        }
    }
}
