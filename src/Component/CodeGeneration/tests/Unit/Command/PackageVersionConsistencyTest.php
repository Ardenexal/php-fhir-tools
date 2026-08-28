<?php

declare(strict_types=1);

/**
 * Guards that every place pinning a FHIR package version agrees, so generated output cannot drift.
 */

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRIGGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
use PHPUnit\Framework\TestCase;

/**
 * Three places pin FHIR package versions: `FHIRIGGeneratorCommand::BASE_PACKAGES`,
 * `FHIRModelGeneratorCommand::DEFAULT_IG_PACKAGES`, and the `generate-models-*` composer scripts.
 *
 * They must agree. `fhir:generate` clears and regenerates `Models/src/{version}` wholesale, while
 * `fhir:generate-ig` also writes into that same tree. When the pins drift, the IG command emits
 * classes `fhir:generate` does not produce; they get committed as generated output and the next
 * regen deletes them. R4 extensions drifted to 5.2.0 here against 5.3.0 in the scripts, which
 * produced two classes for `patient-adoptionInfo` and two for `no-fixed-address` — a duplicate
 * extension URL, which is the collision that breaks URL-to-class resolution.
 *
 * Nothing else catches this: both commands succeed, and the damage only shows as a phantom diff.
 */
class PackageVersionConsistencyTest extends TestCase
{
    /**
     * Read a private array constant off a command class.
     *
     * @param string $class command class holding the pin list
     * @param string $name  constant name to read
     *
     * @return array<string, mixed> the constant's value
     */
    private static function constant(string $class, string $name): array
    {
        /** @var array<string, mixed> $pinsByVersion the constant is always a version-keyed pin list */
        $pinsByVersion = (new \ReflectionClass($class))->getConstant($name);

        return $pinsByVersion;
    }

    /**
     * Collect `package => version` from a nested package list keyed by FHIR version.
     *
     * @param array<string, mixed> $byVersion
     *
     * @return array<string, string> package name → pinned version
     */
    private static function flattenPins(array $byVersion): array
    {
        $pins = [];

        foreach ($byVersion as $specs) {
            self::assertIsArray($specs);
            foreach ($specs as $spec) {
                self::assertIsString($spec);
                self::assertStringContainsString('#', $spec, "Package spec '{$spec}' must pin a version");
                [$name, $version] = explode('#', $spec, 2);
                $pins[$name]      = $version;
            }
        }

        return $pins;
    }

    /**
     * Every package named in a `generate-models-*` composer script must carry an explicit version.
     * An unpinned `--package=name` resolves to whatever is latest, so generated output changes
     * without any change to this repository.
     *
     * @return void
     */
    public function testComposerScriptsPinEveryPackage(): void
    {
        foreach (self::composerScriptPins() as $script => $pins) {
            self::assertNotSame([], $pins, "Script '{$script}' names no packages");
        }
    }

    /**
     * The two commands must not disagree about any package they both name.
     *
     * @return void
     */
    public function testCommandDefaultsAgreeWithEachOther(): void
    {
        $igPins    = self::flattenPins(self::constant(FHIRIGGeneratorCommand::class, 'BASE_PACKAGES'));
        $modelPins = self::flattenPins(self::constant(FHIRModelGeneratorCommand::class, 'DEFAULT_IG_PACKAGES'));

        foreach (array_intersect_key($igPins, $modelPins) as $package => $igVersion) {
            self::assertSame(
                $modelPins[$package],
                $igVersion,
                "'{$package}' is pinned differently by the IG and model generator commands",
            );
        }
    }

    /**
     * The composer scripts must not disagree with the command defaults either — the scripts are how
     * the committed models are actually produced.
     *
     * @return void
     */
    public function testComposerScriptsAgreeWithCommandDefaults(): void
    {
        $defaults = self::flattenPins(self::constant(FHIRModelGeneratorCommand::class, 'DEFAULT_IG_PACKAGES'))
            + self::flattenPins(self::constant(FHIRIGGeneratorCommand::class, 'BASE_PACKAGES'));

        foreach (self::composerScriptPins() as $script => $pins) {
            foreach ($pins as $package => $version) {
                if (!isset($defaults[$package])) {
                    continue;
                }

                self::assertSame(
                    $defaults[$package],
                    $version,
                    "Script '{$script}' pins '{$package}' at {$version}, but the commands use {$defaults[$package]}",
                );
            }
        }
    }

    /**
     * Regenerating without running Pint leaves the whole tree formatted differently, which reads as
     * thousands of changed files. Every generate script must chain the formatter.
     *
     * @return void
     */
    public function testEveryGenerateModelsScriptRunsThePintStep(): void
    {
        foreach (self::generateModelsScripts() as $name => $steps) {
            self::assertContains(
                '@lint:models',
                $steps,
                "Script '{$name}' must run @lint:models, or a regen looks like a repo-wide diff",
            );
        }
    }

    /**
     * The `generate-models-*` scripts, as step lists.
     *
     * @return array<string, list<string>> script name → its steps
     */
    private static function generateModelsScripts(): array
    {
        $path = \dirname(__DIR__, 6) . '/composer.json';
        self::assertFileExists($path, 'Could not locate the root composer.json');

        /** @var array{scripts?: array<string, string|list<string>>} $composer composer.json always decodes to a map */
        $composer = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $scripts  = $composer['scripts'] ?? [];

        $found = [];
        foreach ($scripts as $name => $steps) {
            // Only the versioned generators; bare `generate-models` takes the command defaults.
            if (preg_match('/^generate-models-/', $name) !== 1) {
                continue;
            }

            $found[$name] = array_values((array) $steps);
        }

        self::assertNotSame([], $found, 'No generate-models-* scripts found');

        return $found;
    }

    /**
     * `package => version` for every `--package=` in each `generate-models-*` script.
     *
     * @return array<string, array<string, string>> script name → package pins
     */
    private static function composerScriptPins(): array
    {
        $pins = [];

        foreach (self::generateModelsScripts() as $name => $steps) {
            $pins[$name] = [];

            foreach ($steps as $step) {
                // Capture each --package= argument, whether or not it carries a #version suffix.
                preg_match_all('/--package=(\S+)/', $step, $matches);

                foreach ($matches[1] as $spec) {
                    self::assertStringContainsString(
                        '#',
                        $spec,
                        "Script '{$name}' leaves '{$spec}' unpinned, so it resolves to whatever is latest",
                    );
                    [$package, $version]   = explode('#', $spec, 2);
                    $pins[$name][$package] = $version;
                }
            }
        }

        return $pins;
    }
}
