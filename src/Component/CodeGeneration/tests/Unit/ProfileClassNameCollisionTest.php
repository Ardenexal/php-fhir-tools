<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ClassNameResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The profile half of {@see ExtensionClassNameCollisionTest}.
 *
 * Extensions were given a URL-keyed override map and a test to police it; profiles were not, and
 * `FHIRProfileGenerator` passed the empty string where the URL belongs, so no profile could ever
 * match an override and a collision had no way to be settled. All five of R4B's lipid example
 * profiles declare `name: "Example Lipid Profile"` -- `lipidprofile` (DiagnosticReport) plus
 * `cholesterol`, `hdlcholesterol`, `ldlcholesterol` and `triglyceride` (all Observation) -- so the
 * generator wrote five definitions to one file and four classes were never produced.
 *
 * It surfaced the same way the extension bug did: the drift check reported `ExampleLipidProfile` out
 * of date while a local regeneration reproduced the committed file byte for byte, because the winner
 * follows file enumeration order and CI enumerates differently from a developer machine.
 *
 * Asserted against the vendored packages rather than a list of known-bad names, so a package that
 * introduces a new collision fails here instead of silently shipping one class short.
 *
 * @author Ardenexal
 */
#[CoversClass(ClassNameResolver::class)]
final class ProfileClassNameCollisionTest extends TestCase
{
    /** Package cache written by `fhir:generate`; absent on a clean checkout. */
    private const string PACKAGE_GLOB = __DIR__ . '/../../../../../demo/var/cache/*/.fhir/hl7.fhir.%s.core_*/package';

    /** @return iterable<string, array{string}> */
    public static function versions(): iterable
    {
        yield 'R4'  => ['r4'];
        yield 'R4B' => ['r4b'];
        yield 'R5'  => ['r5'];
    }

    /**
     * No two constraint profiles in a core package may resolve to one class name.
     */
    #[DataProvider('versions')]
    public function testNoTwoProfilesResolveToTheSameClassName(string $version): void
    {
        $dirs = glob(sprintf(self::PACKAGE_GLOB, $version));

        if ($dirs === false || $dirs === []) {
            self::markTestSkipped(sprintf('hl7.fhir.%s.core not cached — run fhir:generate first', $version));
        }

        $byClass = [];

        foreach (glob($dirs[0] . '/StructureDefinition-*.json') ?: [] as $file) {
            $definition = json_decode((string) file_get_contents($file), true);

            if (!is_array($definition)) {
                continue;
            }

            // The same filter buildProfiles() applies, so this measures what actually gets generated.
            if (($definition['resourceType'] ?? null)  !== 'StructureDefinition'
                || ($definition['derivation'] ?? null) !== 'constraint'
                || ($definition['type'] ?? null) === 'Extension'
                || ($definition['kind'] ?? null) === 'logical'
                || !in_array($definition['kind'] ?? null, ['resource', 'complex-type'], true)
            ) {
                continue;
            }

            $url  = is_string($definition['url'] ?? null) ? $definition['url'] : '';
            $name = is_string($definition['name'] ?? null) ? $definition['name'] : '';

            if ($url === '') {
                continue;
            }

            $base = ClassNameResolver::resolveClassName($url, $name);

            $byClass[str_ends_with($base, 'Profile') ? $base : $base . 'Profile'][] = $url;
        }

        self::assertNotSame([], $byClass, 'No profile definitions were read — the glob is wrong, not the packages.');

        $collisions = array_filter($byClass, static fn (array $urls): bool => count($urls) > 1);

        self::assertSame(
            [],
            $collisions,
            sprintf(
                'Two profile definitions resolve to one class, so the generator will silently drop all '
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
     * Each lipid override must rename, and the incumbent must keep the name it already ships under.
     */
    public function testTheLipidOverridesRenameAndLeaveTheIncumbentAlone(): void
    {
        $sharedName = 'Example Lipid Profile';

        $expected = [
            'http://hl7.org/fhir/StructureDefinition/cholesterol'    => 'Cholesterol',
            'http://hl7.org/fhir/StructureDefinition/hdlcholesterol' => 'HdlCholesterol',
            'http://hl7.org/fhir/StructureDefinition/ldlcholesterol' => 'LdlCholesterol',
            'http://hl7.org/fhir/StructureDefinition/triglyceride'   => 'Triglyceride',
        ];

        foreach ($expected as $url => $class) {
            self::assertSame($class, ClassNameResolver::resolveClassName($url, $sharedName), $url);
        }

        // lipidprofile is deliberately NOT overridden: it keeps ExampleLipidProfile, which is the
        // class already published under that name, so this change adds four rather than renaming one.
        self::assertSame(
            'ExampleLipidProfile',
            ClassNameResolver::resolveClassName('http://hl7.org/fhir/StructureDefinition/lipidprofile', $sharedName),
        );
    }
}
