<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonHarness;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\DeclaredLimitations;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\DataProvider;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Pins every declared limitation so the count cannot drift silently.
 *
 * `DeclaredLimitations::MAP` claims a set of corpus cases can never be matched offline. A claim like
 * that is only worth making if it can fail, so each entry is checked four ways: the gap still exists,
 * our count is what we said, the reference validator's count is what we said, and — the one that
 * matters most — the reference validator actually reports something. Declaring a limitation on a case
 * where Java is silent would hide an `ABOVE` rather than document a gap, which is precisely the trap
 * the invariant-keyed suppression this replaces fell into (plan Finding M13).
 *
 * The harness runs once per version and is reused across cases; it takes well under a second.
 */
final class DeclaredLimitationsTest extends TestCase
{
    /** @var array<string, array<string, array{ours: int, java: int}>> */
    private static array $observed = [];

    /**
     * @return iterable<string, array{string, string, array{reason: string, ours: int, java: int}}>
     */
    public static function declaredCases(): iterable
    {
        foreach (DeclaredLimitations::MAP as $version => $cases) {
            foreach ($cases as $name => $entry) {
                yield "{$version} {$name}" => [$version, $name, $entry];
            }
        }
    }

    /**
     * @param array{reason: string, ours: int, java: int} $entry
     */
    #[DataProvider('declaredCases')]
    public function testDeclaredLimitationStillHolds(string $version, string $name, array $entry): void
    {
        $observed = self::observedFor($version);

        self::assertArrayHasKey(
            $name,
            $observed,
            sprintf(
                "'%s' is declared as a %s limitation but the harness no longer compares it. "
                . 'Either the case left the corpus or it stopped deserializing — remove the entry or fix the cause.',
                $name,
                $version,
            ),
        );

        $actual = $observed[$name];

        // The claim that matters: Java must disagree with us. If Java reports nothing, declaring a
        // limitation would suppress an ABOVE case rather than document a gap.
        self::assertGreaterThan(
            0,
            $actual['java'],
            sprintf(
                "'%s' is declared as a limitation, but the reference validator reports no errors on it. "
                . 'A limitation must describe a gap, never hide a false positive of ours.',
                $name,
            ),
        );

        self::assertSame(
            $entry['java'],
            $actual['java'],
            sprintf(
                "Reference-validator error count for '%s' changed (%d -> %d). The vendored corpus moved; "
                . 'update the declared entry deliberately rather than letting it drift.',
                $name,
                $entry['java'],
                $actual['java'],
            ),
        );

        self::assertSame(
            $entry['ours'],
            $actual['ours'],
            sprintf(
                "We now report %d error(s) on '%s', not the declared %d. If the gap closed, delete the "
                . 'entry — a limitation that stopped being one must not linger and go on excusing the case.',
                $actual['ours'],
                $name,
                $entry['ours'],
            ),
        );
    }

    /**
     * The stated reason must be visible in the oracle, not merely asserted in the map.
     *
     * Without this, a fixable gap could be parked here behind a plausible label. An early draft of the
     * map did exactly that to six cases — a canonical type mismatch, a ValueSet expression-language
     * rule, an unknown extension, a fixed-value mismatch, and two others — all of which are ours to fix.
     *
     * @param array{reason: string, ours: int, java: int} $entry
     */
    #[DataProvider('declaredCases')]
    public function testEveryReferenceErrorIsTerminologyBound(string $version, string $name, array $entry): void
    {
        $texts = self::oracleErrorTexts($name);

        self::assertNotEmpty(
            $texts,
            sprintf("No reference-validator errors found for '%s'; the declared limitation is unverifiable.", $name),
        );

        foreach ($texts as $text) {
            $matched = false;
            foreach (DeclaredLimitations::TERMINOLOGY_SIGNATURES as $signature) {
                if (str_contains($text, $signature)) {
                    $matched = true;
                    break;
                }
            }

            self::assertTrue(
                $matched,
                sprintf(
                    "'%s' is declared unreachable offline, but this reference-validator error is not a "
                    . "terminology lookup:\n  %s\nIf it is decidable without a code system, it is ours to fix "
                    . 'and must not be declared here.',
                    $name,
                    $text,
                ),
            );
        }
    }

    /**
     * @return array<string, array{ours: int, java: int}>
     */
    private static function observedFor(string $version): array
    {
        if (isset(self::$observed[$version])) {
            return self::$observed[$version];
        }

        $fhirVersion = match ($version) {
            'R4B'   => FhirVersion::R4B,
            'R5'    => FhirVersion::R5,
            default => FhirVersion::R4,
        };

        $vendorDir = \dirname(__DIR__, 5) . '/vendor';

        $harness = new ComparisonHarness(
            vendorDir: $vendorDir,
            validation: OracleValidationServiceFactory::create($fhirVersion),
            serialization: FHIRSerializationService::createDefault($fhirVersion),
            version: $fhirVersion,
        );

        $observed = [];
        foreach ($harness->run()->comparisons as $comparison) {
            $observed[$comparison->name] = [
                'ours' => $comparison->ourErrorCount,
                'java' => $comparison->javaErrorCount,
            ];
        }

        return self::$observed[$version] = $observed;
    }

    /**
     * @return list<string>
     */
    private static function oracleErrorTexts(string $caseName): array
    {
        $validatorDir = \dirname(__DIR__, 5) . '/vendor/fhir/fhir-test-cases/validator';
        $manifest     = json_decode((string) file_get_contents($validatorDir . '/manifest.json'), true);

        $oraclePath = null;
        foreach ($manifest['test-cases'] ?? [] as $case) {
            if (!is_array($case) || ($case['name'] ?? null) !== $caseName) {
                continue;
            }
            if (is_string($case['java'] ?? null)) {
                $oraclePath = $validatorDir . '/outcomes/' . $case['java'];
            }
            break;
        }

        if ($oraclePath === null || !file_exists($oraclePath)) {
            return [];
        }

        $outcome = json_decode((string) file_get_contents($oraclePath), true);
        $texts   = [];
        foreach ($outcome['issue'] ?? [] as $issue) {
            $severity = $issue['severity'] ?? '';
            if ($severity !== 'error' && $severity !== 'fatal') {
                continue;
            }
            $texts[] = (string) ($issue['details']['text'] ?? '');
        }

        return $texts;
    }
}
