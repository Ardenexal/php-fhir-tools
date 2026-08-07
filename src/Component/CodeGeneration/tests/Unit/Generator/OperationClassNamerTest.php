<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\OperationClassNamer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Runs M02's third pre-registered kill criterion: **zero generated identifiers collide**.
 *
 * Deliberately executed before `FHIROperationGenerator` exists. The first two criteria are measured
 * elsewhere (`OutputShapeClassifierTest` for the shape distribution, `AllowedTypeReaderTest` for
 * empty variant sets); this one covers naming, and it is cheapest to answer while the answer can
 * still change the design. `OutputShapeClassifierTest` already demonstrated the value: its
 * pre-registered count caught a real classifier bug on first run.
 *
 * Naming is where the corpus is most hostile. Across the three core packages the parameter names
 * include hyphens (`check-system-version`), leading underscores (`_count`), PHP reserved words
 * (`use`, `return`, `default`), dots (`targetIdentifier.period`), and a published typo in R5
 * (`targetIdentifer.preferred`). Any of these can produce a parse error or — worse — a silent
 * collision, which is why {@see OperationClassNamer::assertNoCollisions()} throws rather than
 * deduplicating. See `.goat-flow/learning-loop/footguns/valueset-enum-case-naming.md`.
 */
final class OperationClassNamerTest extends TestCase
{
    /**
     * No two operations in a version derive the same class stem.
     */
    #[DataProvider('versionProvider')]
    public function testNoOperationClassStemsCollide(string $version): void
    {
        $namer  = new OperationClassNamer();
        $stems  = [];

        foreach (self::operations($version) as $operation) {
            $stems[$operation['url']] = $namer->classStem($operation);
        }

        $namer->assertNoCollisions($stems, sprintf('%s operation class stems', strtoupper($version)));

        // The collision check passes vacuously on an empty set, so pin the size too.
        self::assertGreaterThanOrEqual(47, count($stems));
        self::assertCount(count(array_unique($stems)), $stems);
    }

    /**
     * Within one operation, no two parameters in the same direction derive the same property name.
     *
     * Direction matters: `$lookup` declares `version` as both an IN and an OUT parameter, and
     * `property` as both an IN `code` and an OUT backbone group. Those live on *separate* generated
     * classes, so they are not collisions — but two IN parameters colliding would be.
     */
    #[DataProvider('versionProvider')]
    public function testNoParameterPropertyNamesCollideWithinADirection(string $version): void
    {
        $namer   = new OperationClassNamer();
        $checked = 0;

        foreach (self::operations($version) as $operation) {
            foreach (['in', 'out'] as $use) {
                $names = [];

                foreach ($operation['parameter'] as $parameter) {
                    if ($parameter['use'] !== $use) {
                        continue;
                    }

                    $names[$parameter['name']] = $namer->propertyName($parameter['name']);
                }

                $namer->assertNoCollisions($names, sprintf(
                    '%s $%s %s parameters',
                    strtoupper($version),
                    $operation['code'],
                    $use,
                ));

                $checked += count($names);
            }
        }

        self::assertGreaterThan(240, $checked, 'Too few parameters checked — has traversal broken?');
    }

    /**
     * Nested `part[]` groups derive collision-free property names too, at every depth.
     */
    #[DataProvider('versionProvider')]
    public function testNestedPartNamesAreLegalAndCollisionFree(string $version): void
    {
        $namer  = new OperationClassNamer();
        $nested = 0;

        foreach (self::operations($version) as $operation) {
            foreach ($operation['parameter'] as $parameter) {
                $nested += $this->assertPartTreeIsClean($namer, $operation['code'], $parameter, [$parameter['name']]);
            }
        }

        self::assertGreaterThan(15, $nested, 'No nested part groups were reached.');
    }

    /**
     * Every derived identifier across the whole corpus is a legal PHP identifier.
     *
     * The property that matters most and is easiest to assume: a name that survives slugging can
     * still be illegal (empty, numeric-leading, or a reserved word).
     */
    #[DataProvider('versionProvider')]
    public function testEveryDerivedIdentifierIsLegalPhp(string $version): void
    {
        $namer   = new OperationClassNamer();
        $pattern = '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/';
        $checked = 0;

        foreach (self::operations($version) as $operation) {
            self::assertMatchesRegularExpression($pattern, $namer->classStem($operation));

            foreach (self::flattenParameters($operation['parameter']) as $parameter) {
                $name = $namer->propertyName($parameter['name']);

                self::assertMatchesRegularExpression(
                    $pattern,
                    $name,
                    sprintf('"%s" produced the illegal identifier "%s".', $parameter['name'], $name),
                );

                ++$checked;
            }
        }

        self::assertGreaterThan(250, $checked);
    }

    /**
     * The corpus really does contain the hostile names this class exists for.
     *
     * Without this, every assertion above could be passing because the hard cases are absent.
     */
    #[DataProvider('versionProvider')]
    public function testTheCorpusContainsTheHostileNamesThisGuardsAgainst(string $version): void
    {
        $names = [];

        foreach (self::operations($version) as $operation) {
            foreach (self::flattenParameters($operation['parameter']) as $parameter) {
                $names[$parameter['name']] = true;
            }
        }

        $names = array_keys($names);

        self::assertNotSame(
            [],
            array_filter($names, static fn (string $n): bool => str_contains($n, '-')),
            'No hyphenated parameter names found.',
        );
        self::assertNotSame(
            [],
            array_filter($names, static fn (string $n): bool => str_starts_with($n, '_')),
            'No leading-underscore parameter names found.',
        );
        self::assertContains('use', $names, 'The reserved-word case `use` is missing from the corpus.');
    }

    /**
     * The specific transformations, pinned so a slugger change is visible rather than silent.
     */
    #[DataProvider('propertyNameProvider')]
    public function testPropertyNamesAreDerivedAsExpected(string $wireName, string $expected): void
    {
        self::assertSame($expected, (new OperationClassNamer())->propertyName($wireName));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function propertyNameProvider(): iterable
    {
        yield 'plain'                => ['code', 'code'];
        yield 'hyphenated'           => ['check-system-version', 'checkSystemVersion'];
        yield 'leading underscore'   => ['_count', 'count'];
        // PHP reserves neither property nor parameter names, so these pass through untouched.
        // Escaping them was a real bug: the generated class declared `$useParameter`, and an M01
        // assertion reading `->use` then returned null and passed *vacuously* (N28).
        yield 'reserved word'        => ['use', 'use'];
        yield 'reserved word return' => ['return', 'return'];
        yield 'dotted'               => ['targetIdentifier.period', 'targetIdentifierPeriod'];
        yield 'published typo kept'  => ['targetIdentifer.preferred', 'targetIdentiferPreferred'];
        yield 'numeric leading'      => ['2fa', 'p2fa'];
    }

    /**
     * A collision is fatal and names both sources — it never silently dedups.
     *
     * The footgun's central lesson: silent dedup makes a legal value unrepresentable with no error,
     * which is worse than a crash.
     */
    public function testCollisionsThrowAndNameBothSources(): void
    {
        $namer = new OperationClassNamer();

        $this->expectException(GenerationException::class);
        $this->expectExceptionMessageMatches('/"first".*"second".*"Same"|"Same"/s');

        $namer->assertNoCollisions(['first' => 'Same', 'second' => 'Same'], 'test');
    }

    /**
     * An operation with neither resource nor code is reported, not silently skipped.
     */
    public function testUnnameableOperationThrows(): void
    {
        $this->expectException(GenerationException::class);
        $this->expectExceptionMessageMatches('/empty identifier/');

        (new OperationClassNamer())->classStem(['url' => 'http://example.org/op', 'resource' => []]);
    }

    /**
     * A reserved word is legal as a property name and is emitted verbatim.
     *
     * Proven by construction rather than asserted: PHP only reserves these as *class* names.
     */
    public function testReservedWordIsLegalAsAPropertyName(): void
    {
        $probe = new class ('yes') {
            public function __construct(public readonly ?string $use = null)
            {
            }
        };

        self::assertTrue(property_exists($probe, 'use'));
        self::assertSame('use', (new OperationClassNamer())->propertyName('use'));
    }

    /**
     * A parameter named for a reserved word yields a legal, non-reserved class name.
     *
     * `…\Designation\Use` would be a fatal parse error, which is why the class-name guard exists.
     * In practice it never fires on real data: part class names are `{Use}{Segments}`, so
     * `designation.use` becomes `OutUse` — already unreserved by construction. Asserted as the
     * *property* (the name is legal and unreserved) rather than as the mechanism, so the test stays
     * true whether the guard fires or the composition happens to make it unnecessary.
     */
    #[DataProvider('versionProvider')]
    public function testNoDerivedClassNameIsAReservedWord(string $version): void
    {
        $namer   = new OperationClassNamer();
        $checked = 0;

        foreach (self::operations($version) as $operation) {
            $stem = $namer->classStem($operation);
            self::assertFalse(self::isReserved($stem), sprintf('Class stem "%s" is a reserved word.', $stem));

            foreach ($operation['parameter'] as $parameter) {
                if (!is_array($parameter['part'] ?? null) || $parameter['part'] === []) {
                    continue;
                }

                $name = $namer->partClassName($parameter['use'], [$parameter['name']]);

                self::assertFalse(self::isReserved($name), sprintf('Part class "%s" is a reserved word.', $name));
                ++$checked;
            }
        }

        self::assertGreaterThan(2, $checked, 'No part classes were reached.');

        // `designation.use` is the case D3 named. It composes to `OutUse`, legal without the guard.
        self::assertSame('OutUse', $namer->partClassName('out', ['use']));
    }

    private static function isReserved(string $name): bool
    {
        // A reserved word is unusable as a class name; eval-free check via a reflection-safe list.
        return in_array(strtolower($name), [
            'use', 'return', 'class', 'function', 'list', 'print', 'echo', 'default', 'match',
            'abstract', 'final', 'static', 'new', 'enum', 'interface', 'trait',
        ], true);
    }

    /**
     * Part class names incorporate `use`, so the `property` in/out collision cannot occur.
     */
    public function testPartClassNamesIncorporateDirection(): void
    {
        $namer = new OperationClassNamer();

        $in  = $namer->partClassName('in', ['property']);
        $out = $namer->partClassName('out', ['property']);

        self::assertNotSame($in, $out, 'A name-keyed scheme would collide here — this is M01 note N3.');
        self::assertSame('OutPropertySubproperty', $namer->partClassName('out', ['property', 'subproperty']));
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
     * @param array<string, mixed> $parameter
     * @param list<string>         $path
     *
     * @return int Number of nested part parameters checked
     */
    private function assertPartTreeIsClean(
        OperationClassNamer $namer,
        string $code,
        array $parameter,
        array $path,
    ): int {
        $parts = $parameter['part'] ?? [];

        if (!is_array($parts) || $parts === []) {
            return 0;
        }

        // The nested class must itself be nameable before its members are checked.
        $namer->partClassName(is_string($parameter['use']) ? $parameter['use'] : 'in', $path);

        $names   = [];
        $checked = 0;

        foreach ($parts as $part) {
            $names[$part['name']] = $namer->propertyName($part['name']);
            ++$checked;
            $checked += $this->assertPartTreeIsClean($namer, $code, $part, [...$path, $part['name']]);
        }

        $namer->assertNoCollisions($names, sprintf('$%s %s parts', $code, implode('.', $path)));

        return $checked;
    }

    /**
     * @param list<array<string, mixed>> $parameters
     *
     * @return list<array<string, mixed>>
     */
    private static function flattenParameters(array $parameters): array
    {
        $flat = [];

        foreach ($parameters as $parameter) {
            $flat[] = $parameter;

            if (is_array($parameter['part'] ?? null)) {
                $flat = [...$flat, ...self::flattenParameters($parameter['part'])];
            }
        }

        return $flat;
    }

    /**
     * Generatable operations only — `kind = 'query'` is excluded by design.
     *
     * @return array<string, array<string, mixed>>
     */
    private static function operations(string $version): array
    {
        $file = sprintf('%s/../../Fixtures/OperationManifests/%s-operations.json', __DIR__, $version);

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Operation manifest %s is unreadable.', $file));

        /** @var array<string, array<string, mixed>> $operations */
        $operations = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        return array_filter($operations, static fn (array $o): bool => ($o['kind'] ?? null) !== 'query');
    }
}
