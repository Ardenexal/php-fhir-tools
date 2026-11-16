<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Support;

use Ardenexal\FHIRTools\Component\CodeGeneration\Support\StringCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\AbstractString;

use function Symfony\Component\String\u;

/**
 * Guards the generators against calling symfony/string methods that the declared lower bound
 * does not ship.
 *
 * composer.json declares `symfony/string: ^6.4|^7.0`, but `AbstractString::pascal()` only
 * arrived in 7.3 and `kebab()` in 7.2. Calling either directly threw
 * `Call to undefined method` on 6.4 through 7.2, which failed every StructureDefinition and
 * produced no classes at all.
 *
 * Two things are asserted, because neither alone holds the line:
 *
 * 1. {@see testMatchesUpstreamPascal} proves the replacement is byte-identical to upstream. It
 *    can only run where `pascal()` exists, so it skips on the very versions the fix targets.
 *    On CI it discharges the "generated class names must not change between framework versions"
 *    requirement, which matters more than the crash, since a divergence would silently rename
 *    generated classes instead of failing loudly.
 * 2. {@see testGeneratorsDoNotCallVersionGatedMethods} runs everywhere and is what actually
 *    prevents reintroduction.
 *
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Support\StringCase
 */
final class StringCaseTest extends TestCase
{
    /**
     * Inputs drawn from the shapes the generators actually feed in: IG StructureDefinition ids,
     * extension codes, FHIR primitive type codes and element paths, plus the casing edge cases
     * where a naive PascalCase implementation would diverge from upstream.
     *
     * @return iterable<string, array{string}>
     */
    public static function identifierProvider(): iterable
    {
        $cases = [
            // hl7.fhir.au.base StructureDefinition ids
            'au-ihi', 'au-patient', 'au-medicareprovidernumber', 'au_paid_identifier',
            'identifier-routability', 'ihi-verified-date',
            // FHIR primitive and element shapes
            'value', 'string', 'boolean', 'Patient.contact', 'MedicationRequest.dosageInstruction',
            // casing edge cases: runs of capitals must survive untouched
            'AUCorePatient', 'XMLHttpRequest', 'ABC', 'already Pascal', 'a',
            // separator and non-ASCII edge cases
            'au--double--dash', '2fa-token', 'Ünicode-tëst', '',
        ];

        foreach ($cases as $case) {
            yield ($case === '' ? '(empty string)' : $case) => [$case];
        }
    }

    /**
     * The replacement must equal `pascal()` exactly, not merely look like PascalCase.
     *
     * Equivalence is true by construction, since upstream defines `pascal()` as `camel()->title()`
     * on `AbstractString` with no subclass override. The transcription is still asserted rather
     * than trusted, because these strings become generated class names.
     */
    #[DataProvider('identifierProvider')]
    public function testMatchesUpstreamPascal(string $source): void
    {
        if (!method_exists(AbstractString::class, 'pascal')) {
            self::markTestSkipped('symfony/string < 7.3 has no pascal() to compare against.');
        }

        self::assertSame(u($source)->pascal()->toString(), StringCase::pascal($source));
    }

    /**
     * Pins the expected output directly, so the contract still has teeth on 6.4 through 7.2 where
     * the differential test above skips.
     */
    public function testPascalProducesExpectedClassNames(): void
    {
        self::assertSame('AuIhi', StringCase::pascal('au-ihi'));
        self::assertSame('IdentifierRoutability', StringCase::pascal('identifier-routability'));
        self::assertSame('AuPaidIdentifier', StringCase::pascal('au_paid_identifier'));
        self::assertSame('PatientContact', StringCase::pascal('Patient.contact'));
        self::assertSame('Value', StringCase::pascal('value'));
        self::assertSame('AUCorePatient', StringCase::pascal('AUCorePatient'));
        self::assertSame('', StringCase::pascal(''));
    }

    /**
     * The same equivalence proof for `kebab()`, which is unused today but shimmed alongside
     * `pascal()` so that adopting it later cannot break the lower bound again.
     */
    #[DataProvider('identifierProvider')]
    public function testMatchesUpstreamKebab(string $source): void
    {
        if (!method_exists(AbstractString::class, 'kebab')) {
            self::markTestSkipped('symfony/string < 7.2 has no kebab() to compare against.');
        }

        self::assertSame(u($source)->kebab()->toString(), StringCase::kebab($source));
    }

    /**
     * The assertion that survives a symfony/string upgrade: no source file may call the
     * version-gated methods directly, whichever version happens to be installed locally.
     *
     * {@see StringCase} itself is exempt, because it names both methods in its docblock to explain
     * what the shim replaces.
     */
    public function testGeneratorsDoNotCallVersionGatedMethods(): void
    {
        $sourceRoot = \dirname(__DIR__, 3) . '/src';
        $offenders  = [];

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sourceRoot));

        foreach ($files as $file) {
            \assert($file instanceof \SplFileInfo);

            if ($file->getExtension() !== 'php' || $file->getFilename() === 'StringCase.php') {
                continue;
            }

            $contents = (string) file_get_contents($file->getPathname());

            // Matches the method-call form only, so a mention inside prose or a variable named
            // "pascal" does not register as a call site.
            if (preg_match('/->(pascal|kebab)\(\)/', $contents, $matches) === 1) {
                $offenders[] = substr($file->getPathname(), \strlen($sourceRoot) + 1) . " calls ->{$matches[1]}()";
            }
        }

        self::assertSame(
            [],
            $offenders,
            'pascal() needs symfony/string 7.3 and kebab() needs 7.2, but this component declares '
            . '^6.4|^7.0. Use StringCase::pascal()/::kebab() instead.',
        );
    }
}
