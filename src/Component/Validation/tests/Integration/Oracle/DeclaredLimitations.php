<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * Corpus cases we can never match offline, declared explicitly rather than absorbed into seed counts.
 *
 * Every entry here is a case where **all** of the reference validator's errors need a code system we
 * do not have and cannot obtain: LOINC, SNOMED CT and CVX are license-restricted and are not vendored
 * in any form. Deciding them needs a terminology server, so no amount of work on this codebase closes
 * them. They are documentation, not backlog.
 *
 * Why a map rather than a rule in `isKnownGap()`: the suppression this replaces was keyed by
 * *invariant key*, which meant it silently swallowed anything matching the rule — including, as it
 * turned out, a genuine finding the reference validator agreed with (`R5.list-contained-bad`, see
 * plan Finding M13). Keying by case, and pinning both sides' counts, makes each claim falsifiable:
 *
 *  - if we start reporting a case, the pin fails and the entry must be removed — a limitation that
 *    stopped being one cannot linger;
 *  - if the vendored corpus changes the reference counts, the pin fails rather than drifting;
 *  - if the reference validator reports **nothing**, the entry is rejected outright, because
 *    declaring a limitation where Java is silent would hide an `ABOVE` case rather than document a gap.
 *
 * Deliberately excluded, because they are ours to fix and must not hide here:
 * `qr-bad-ref2` (canonical resolves to the wrong resource type), `vs-params-4` (a ValueSet expression
 * language rule), `contained-canonical` (unknown extension), `obs-de-notx` (a fixed-value mismatch),
 * `mimic` (its package *is* vendored — the instance has a system-URI typo), and `obs-temp-bad`
 * (the reference validator's hard-coded vitals code table, a different kind of limitation entirely).
 *
 * Also excluded: every case whose Java errors are only *partly* terminology-bound. Those stay `BELOW`
 * on their own merits, and declaring them would write off fixable work — `mr-covid-bnd1` alone has 13
 * offline-decidable errors behind 13 terminology ones.
 */
final class DeclaredLimitations
{
    /**
     * Reason codes. Each names the *obstacle*, not the symptom.
     */
    public const REASON_LOINC = 'LOINC display names and codes are not distributable and are not vendored';

    public const REASON_SNOMED = 'SNOMED CT is licence-restricted, dated, and is not vendored';

    public const REASON_CVX = 'CVX vaccine code displays are not vendored';

    public const REASON_HL7_DISPLAY = 'display validation against a vendored CodeSystem is not implemented';

    /**
     * case name => [reason, ourErrorCount, javaErrorCount]
     *
     * `ourErrorCount` is what we legitimately report today — 0 for all current entries, but recorded
     * explicitly so a case that gains a *partial* finding fails the pin instead of passing silently.
     *
     * @var array<string, array<string, array{reason: string, ours: int, java: int}>>
     */
    public const MAP = [
        'R4' => [
            'sdoh-type-slice'          => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'ips-nz-pj'                => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 4],
            'uk-msg'                   => ['reason' => self::REASON_SNOMED, 'ours' => 0, 'java' => 4],
            'bundle-id-5'              => ['reason' => self::REASON_CVX, 'ours' => 0, 'java' => 1],
            'patient-translated-codes' => ['reason' => self::REASON_HL7_DISPLAY, 'ours' => 0, 'java' => 2],
        ],
        'R4B' => [],
        'R5'  => [
            'observation-cholesterol-good'           => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'observation-triglyceride-good'          => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'observation-triglyceride-good2'         => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'observation-triglyceride-bad-wrongcode' => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'demo-example-2'                         => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
        ],
    ];

    /**
     * Substrings that mark a reference-validator error as needing a code system we do not hold.
     *
     * Used to prove each declared reason against the oracle rather than trusting the label. Kept
     * narrow on purpose: a broad list would match structural findings too and let fixable work be
     * written off — an early draft of this map wrongly captured six such cases.
     *
     * @var list<string>
     */
    public const TERMINOLOGY_SIGNATURES = [
        'Wrong Display Name',
        'Unknown code',
        'was not found in the value set',
        'None of the codings provided are in the value set',
        'defined in the profile', // pattern mismatches whose expected display comes from the code system
    ];

    /** @return array<string, array{reason: string, ours: int, java: int}> */
    public static function forVersion(string $version): array
    {
        return self::MAP[$version] ?? [];
    }

    /** Total reference-validator errors written off across every version. */
    public static function totalJavaErrors(): int
    {
        $total = 0;
        foreach (self::MAP as $cases) {
            foreach ($cases as $entry) {
                $total += $entry['java'];
            }
        }

        return $total;
    }
}
