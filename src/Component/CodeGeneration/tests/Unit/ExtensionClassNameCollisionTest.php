<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ClassNameResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Two definitions must never resolve to the same class name.
 *
 * `ClassNameResolver` derives a class from a definition's `name`, which is not unique within a
 * package. When two collide the generator writes both to the same file and the last one wins, so the
 * loser has no class at all and its URL resolves to nothing when a document carrying it is
 * deserialized. Nothing reports that — the output is simply one class short.
 *
 * The failure surfaced as a phantom: the model-generation drift check reported
 * `PublicationDateExtension` out of date, while regenerating locally reproduced the committed file
 * byte for byte. The winner depends on the order the package's files are enumerated in, so the
 * "correct" output differed between the CI runner and a developer machine and no amount of
 * regenerating could settle it.
 *
 * This test asserts the property directly against the vendored packages rather than against a list of
 * known-bad names, so a future package that introduces a fourth collision fails here instead of
 * silently dropping an extension.
 */
#[CoversClass(ClassNameResolver::class)]
final class ExtensionClassNameCollisionTest extends TestCase
{
    /** Package cache written by `fhir:generate`; absent on a clean checkout. */
    private const PACKAGE_GLOB = __DIR__ . '/../../../../../demo/var/cache/*/.fhir/hl7.fhir.uv.extensions.%s_*/package';

    /** @return iterable<string, array{string}> */
    public static function versions(): iterable
    {
        yield 'R4'  => ['r4'];
        yield 'R4B' => ['r4b'];
        yield 'R5'  => ['r5'];
    }

    #[DataProvider('versions')]
    public function testNoTwoExtensionsResolveToTheSameClassName(string $version): void
    {
        $dirs = glob(sprintf(self::PACKAGE_GLOB, $version));

        if ($dirs === false || $dirs === []) {
            self::markTestSkipped(sprintf('hl7.fhir.uv.extensions.%s not cached — run fhir:generate first', $version));
        }

        $byClass = [];

        foreach (glob($dirs[0] . '/StructureDefinition-*.json') ?: [] as $file) {
            $definition = json_decode((string) file_get_contents($file), true);

            if (!is_array($definition) || ($definition['type'] ?? null) !== 'Extension') {
                continue;
            }

            $url  = is_string($definition['url'] ?? null) ? $definition['url'] : '';
            $name = is_string($definition['name'] ?? null) ? $definition['name'] : '';

            $byClass[ClassNameResolver::resolveClassName($url, $name) . 'Extension'][] = $url;
        }

        self::assertNotSame([], $byClass, 'No extension definitions were read — the glob is wrong, not the packages.');

        $collisions = array_filter($byClass, static fn (array $urls): bool => count($urls) > 1);

        self::assertSame(
            [],
            $collisions,
            sprintf(
                'Two extension definitions resolve to one class, so the generator will silently drop all '
                . "but the last and the winner depends on file enumeration order:\n%s\n"
                . 'Add a DEFINITION_TO_CLASS_OVERRIDES entry for the definition that should be renamed.',
                implode("\n", array_map(
                    static fn (string $class, array $urls): string => sprintf('  %s <- %s', $class, implode(', ', $urls)),
                    array_keys($collisions),
                    $collisions,
                )),
            ),
        );
    }

    /**
     * The overrides exist to break ties, so each must actually change the name it is keyed to.
     * An entry that agrees with the default rule is dead weight and hides that the tie is unresolved.
     */
    public function testEveryCollisionOverrideActuallyRenames(): void
    {
        $cases = [
            'http://hl7.org/fhir/StructureDefinition/artifact-publicationDate' => ['PublicationDate', 'ArtifactPublicationDate'],
            'http://hl7.org/fhir/StructureDefinition/event-partOf'             => ['PartOf', 'EventPartOf'],
            'http://hl7.org/fhir/StructureDefinition/tz-code'                  => ['TimezoneCode', 'TzCode'],
        ];

        foreach ($cases as $url => [$fhirName, $expected]) {
            self::assertSame($expected, ClassNameResolver::resolveClassName($url, $fhirName), $url);
            // And the incumbent keeps the plain name, so this is additive rather than a rename.
            self::assertSame($fhirName, ClassNameResolver::resolveClassName('http://example.org/not-overridden', $fhirName));
        }
    }
}
