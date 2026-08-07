<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\VariantOrderer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Checks `VariantOrderer`'s spec-derived ordering against the *real* generated PHP class hierarchy.
 *
 * `VariantOrderer` ranks types by their `baseDefinition` chain because CodeGeneration must not depend
 * on Models — a constraint two tests defend (`CodeGenerationIndependenceTest`,
 * `CodeGenerationDependencyMinimizationTest`), and one that ships as a real Composer boundary. The
 * ordering it produces is nonetheless only correct if that specification chain matches the PHP
 * `extends` chain the model generator emits, and **nothing inside either component can check that**:
 * CodeGeneration cannot see Models, and Serialization cannot see CodeGeneration.
 *
 * So the check lives here, in the monorepo-level suite, where both are on the autoloader and no
 * component's standalone distribution is affected. This is the assertion that keeps the ordering
 * drift-proof: if a future FHIR version re-parents a primitive, or the model generator changes what
 * a wrapper extends, the two stop agreeing and this fails — rather than a `UrlPrimitive` silently
 * serializing as `valueUri` in production.
 *
 * See `.goat-flow/learning-loop/footguns/choice-variant-ordering.md`.
 */
final class VariantOrderingMatchesModelInheritanceTest extends TestCase
{
    /**
     * Every subclass relationship among the generated primitives is reflected by a greater depth.
     *
     * Discovers the pairs by reflecting on the shipped classes rather than listing them, so a
     * newly-generated primitive is covered the moment it exists.
     */
    #[DataProvider('versionProvider')]
    public function testEverySubclassOutranksItsParent(string $version): void
    {
        $orderer  = new VariantOrderer();
        $context  = self::context($version);
        $checked  = 0;

        foreach (self::primitiveClasses($version) as $typeCode => $class) {
            $parent = get_parent_class($class);

            if ($parent === false) {
                continue;
            }

            $parentCode = self::typeCodeFor($parent, $version);

            // Only the primitive→primitive links matter: a wrapper whose parent is an abstract base
            // outside the primitive set has no competing variant to be confused with.
            if ($parentCode === null) {
                continue;
            }

            self::assertGreaterThan(
                $orderer->depthOf($parentCode, $context),
                $orderer->depthOf($typeCode, $context),
                sprintf(
                    'PHP has %s extends %s, but the specification chain does not rank "%s" deeper '
                    . 'than "%s" — a %s would serialize under the wrong value[x] key.',
                    self::shortName($class),
                    self::shortName($parent),
                    $typeCode,
                    $parentCode,
                    self::shortName($class),
                ),
            );

            ++$checked;
        }

        self::assertGreaterThanOrEqual(
            8,
            $checked,
            'Found too few primitive subclass pairs to be a meaningful check — has discovery broken?',
        );
    }

    /**
     * The ordered list never places a class ahead of one of its own subclasses.
     *
     * The property stated directly against `instanceof` semantics: for the whole primitive set at
     * once, walking the ordered list must never encounter a type whose PHP class is a parent of one
     * appearing later. That is exactly the condition `resolveChoiceVariant` depends on.
     */
    #[DataProvider('versionProvider')]
    public function testNoTypePrecedesItsOwnSubclassInTheFullOrdering(string $version): void
    {
        $classes = self::primitiveClasses($version);
        $ordered = (new VariantOrderer())->order(array_keys($classes), self::context($version));

        foreach ($ordered as $earlierIndex => $earlier) {
            foreach (array_slice($ordered, $earlierIndex + 1) as $later) {
                self::assertFalse(
                    is_subclass_of($classes[$later], $classes[$earlier]),
                    sprintf(
                        '"%s" is ordered before "%s", but %s extends %s — the supertype would '
                        . 'capture the subtype\'s values.',
                        $earlier,
                        $later,
                        self::shortName($classes[$later]),
                        self::shortName($classes[$earlier]),
                    ),
                );
            }
        }
    }

    /**
     * The `{uri, url}` pair specifically — the one alphabetical sorting gets wrong.
     */
    #[DataProvider('versionProvider')]
    public function testTheKnownUnsafePairAgreesWithRealInheritance(string $version): void
    {
        $classes = self::primitiveClasses($version);

        self::assertTrue(
            is_subclass_of($classes['url'], $classes['uri']),
            'UrlPrimitive no longer extends UriPrimitive — the footgun premise has changed.',
        );

        self::assertSame(
            ['url', 'uri'],
            (new VariantOrderer())->order(['uri', 'url'], self::context($version)),
        );
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4'  => ['R4'];
        yield 'R4B' => ['R4B'];
        yield 'R5'  => ['R5'];
    }

    /**
     * Every generated primitive wrapper for a version, keyed by FHIR type code.
     *
     * @return array<string, class-string>
     */
    private static function primitiveClasses(string $version): array
    {
        $directory = sprintf('%s/../../src/Component/Models/src/%s/Primitive', __DIR__, $version);

        $files = glob($directory . '/*Primitive.php');
        self::assertIsArray($files);
        self::assertNotEmpty($files, sprintf('No generated primitives found for %s.', $version));

        $classes = [];

        foreach ($files as $file) {
            $shortName = basename($file, '.php');
            $class     = sprintf('Ardenexal\FHIRTools\Component\Models\%s\Primitive\%s', $version, $shortName);

            if (!class_exists($class)) {
                continue;
            }

            // `CodePrimitive` -> `code`. The models name wrappers after the type code, so this is a
            // reversal of the generator's own convention rather than an independent guess.
            $typeCode = lcfirst(substr($shortName, 0, -strlen('Primitive')));

            $classes[$typeCode] = $class;
        }

        return $classes;
    }

    private static function typeCodeFor(string $class, string $version): ?string
    {
        return array_search($class, self::primitiveClasses($version), true) ?: null;
    }

    private static function shortName(string $class): string
    {
        $position = strrpos($class, '\\');

        return $position === false ? $class : substr($class, $position + 1);
    }

    /**
     * A context carrying the published type index for this version.
     *
     * Each version has its own index, extracted from its own package — R4B's type set differs from
     * R4's (206 types vs 209), so sharing a fixture would quietly test the wrong hierarchy.
     */
    private static function context(string $version): BuilderContext
    {
        $tag  = strtolower($version);
        $file = sprintf(
            '%s/../../src/Component/CodeGeneration/tests/Fixtures/TypeIndex/%s-type-index.json',
            __DIR__,
            $tag,
        );

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Type index %s is unreadable.', $file));

        /** @var array<string, array<string, mixed>> $definitions */
        $definitions = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        $context = new BuilderContext();
        $context->loadDefinitions($definitions);

        return $context;
    }
}
