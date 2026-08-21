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

    /** ICD-10 in every dialect: the base classification and the German `dimdi` variant. */
    public const REASON_ICD10 = 'ICD-10 is licence-restricted and no vendored package carries its concepts';

    /** The NCI Thesaurus, reached via a Coding in `bundle-with-contained`. */
    public const REASON_NCI = 'the NCI Thesaurus is not vendored in any form';

    /**
     * A national or project guide we have no copy of.
     *
     * The corpus ships a few packages of its own — `mimic`, `swiss.mednet.fhir`, `hl7.fhir.test.versions` —
     * so "not in the package cache" is not the same as "unavailable". This reason is only for systems that
     * are in neither place. `mimic` in particular is shipped, at
     * `vendor/fhir/fhir-test-cases/validator/mimic/mimic-0.1.2.tgz`, and is ours to fix.
     */
    public const REASON_UNVENDORED_IG = 'the implementation guide defining this system is not vendored, and the corpus does not ship it';

    /**
     * Code system => why no work on this codebase can decide a finding that names it.
     *
     * Keyed on the **obstacle** rather than the case, because the measurement is finding-level now: one
     * case can hold a LOINC display finding we can never decide beside a cardinality finding that is
     * plainly ours, and `japanese-utf8-ok` holds 108 findings of which only some are terminology. Declaring
     * whole cases cannot express that, which is why {@see MAP} above deliberately refuses cases that are
     * only partly terminology-bound.
     *
     * A system-keyed rule is the same *shape* as the invariant-keyed suppression this class replaced, and
     * that shape failed by absorbing whatever matched. What made the case map safe was not its key but its
     * **pinned counts**, so that property is kept: see {@see DECLARED_FINDING_COUNTS}. A new LOINC finding
     * appearing must fail a test rather than quietly joining the written-off pile.
     *
     * Verified 2026-08-20 against what is actually reachable at validation time. That is **not** the
     * package cache under `~/.fhir/packages` — only `CodeGeneration` reads that. The validator resolves
     * codes against generated enums (`FHIRValueSetBindingValidator` takes `$enumNamespaceRoots` pointing at
     * `Models\{R4,R4B,R5}\Enum`), so a system is decidable here only if its ValueSet became an enum.
     *
     * @var array<string, string>
     */
    public const TERMINOLOGY_SYSTEMS = [
        'http://loinc.org'                                   => self::REASON_LOINC,
        'http://snomed.info/sct'                             => self::REASON_SNOMED,
        'http://hl7.org/fhir/sid/cvx'                        => self::REASON_CVX,
        'http://hl7.org/fhir/sid/icd-10'                     => self::REASON_ICD10,
        'http://hl7.org/fhir/ValueSet/icd-10'                => self::REASON_ICD10,
        'http://fhir.de/CodeSystem/dimdi/icd-10-gm'          => self::REASON_ICD10,
        'http://fhir.de/ValueSet/dimdi/icd-10-gm'            => self::REASON_ICD10,
        'http://ncicb.nci.nih.gov/xml/owl/EVS/Thesaurus.owl' => self::REASON_NCI,
        'http://ehelse.no/fhir'                              => self::REASON_UNVENDORED_IG,
    ];

    /** Refusing a DOCTYPE is the right behaviour, so the reference finding behind it is unreachable. */
    public const REASON_XXE_REFUSAL = 'refusing an XML DOCTYPE is deliberate: it blocks external-entity attacks, so this finding is unreachable by design';

    /**
     * Text signature => reason, for limitations that are not about a code system.
     *
     * Kept apart from {@see TERMINOLOGY_SYSTEMS} because the two are different claims. A terminology entry
     * says "we have no copy of this data"; an entry here says "we decline to do this, and would decline
     * again". `list-xhtml-xxe1` is the whole of it: the reference validator parses a document declaring a
     * DOCTYPE, we refuse, and refusing is correct — `disallow-doctype-decl` is what stops an external-entity
     * attack. Counting that as a gap would put pressure on a security control.
     *
     * @var array<string, string>
     */
    public const DECLARED_SIGNATURES = [
        'doctype is disallowed'       => self::REASON_XXE_REFUSAL,
        'found a doctype declaration' => self::REASON_XXE_REFUSAL,
    ];

    /**
     * version => reason => how many findings it blocks, as measured 2026-08-20.
     *
     * The property that made the case-keyed {@see MAP} safe, kept for the system-keyed rule: a claim that
     * cannot fail is not worth making. Pinned by `DeclaredLimitationsTest`, so a new LOINC finding has to
     * fail a test rather than quietly join the written-off pile — which is exactly how the invariant-keyed
     * suppression this class replaced went wrong.
     *
     * Update these only after reading why the number moved. Growing means more findings are being written
     * off; shrinking means a limitation stopped being one and its entry should go.
     *
     * @var array<string, array<string, int>>
     */
    public const EXPECTED_FINDING_COUNTS = [
        'R4' => [
            self::REASON_LOINC  => 27,
            self::REASON_SNOMED => 19,
            self::REASON_ICD10  => 4,
            self::REASON_CVX    => 3,
            self::REASON_NCI    => 1,
        ],
        'R4B' => [],
        'R5'  => [
            self::REASON_LOINC       => 7,
            self::REASON_XXE_REFUSAL => 1,
        ],
    ];

    /**
     * The reason a finding cannot be decided offline, or null when nothing here blocks it.
     *
     * Matched on the raw text because the system URL is the only part of a reference message that names the
     * obstacle. Deliberately narrow: a system absent from {@see TERMINOLOGY_SYSTEMS} yields null and the
     * finding stays counted as open, which is the direction that keeps work visible.
     */
    public static function reasonFor(string $javaText): ?string
    {
        foreach (self::TERMINOLOGY_SYSTEMS as $system => $reason) {
            if (str_contains($javaText, $system)) {
                return $reason;
            }
        }

        $haystack = strtolower($javaText);
        foreach (self::DECLARED_SIGNATURES as $signature => $reason) {
            if (str_contains($haystack, $signature)) {
                return $reason;
            }
        }

        return null;
    }

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
