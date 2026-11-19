<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\FHIRModifierExtensionValidationTest;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;

/**
 * Corpus cases we can never match offline, declared explicitly rather than absorbed into seed counts.
 *
 * Every entry here is a case where **all** of the reference validator's errors are blocked by one
 * obstacle no work on this codebase removes. There are two such obstacles.
 *
 * The first is an unobtainable code system: LOINC, SNOMED CT and CVX are license-restricted and are
 * not vendored in any form, so deciding them needs a terminology server.
 *
 * The second is a recorded design decision, which is just as final until the decision changes. See
 * {@see REASON_UNKNOWN_EXTENSION}: this project does not report an unresolvable regular extension,
 * and {@see FHIRModifierExtensionValidationTest}
 * pins that. Findings the reference validator raises under that rule can never be matched while the
 * decision stands, so they are documentation rather than backlog. But unlike a licence, a decision
 * can be revisited, and these entries are where to look when it is.
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
 * `vs-params-4` (a ValueSet expression language rule),
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

    /**
     * Display text is checked by a terminology server, not by this codebase.
     *
     * The reference validator answers "is this display right for this code" with a set of
     * language-tagged alternatives, which is what a `$validate-code` call returns. The contract for it
     * already exists here — {@see FHIRTerminologyClientInterface::validateCodingWithDisplay()}
     * returns the corrected display — but `FHIRValidationService` is constructed with no terminology
     * client at all, so general resource validation has no path to one. The only production caller is
     * `FHIRQuestionnaireValidator`.
     *
     * Wiring one in is worth doing for callers who have a server, and it would answer LOINC and SNOMED
     * displays that no amount of vendoring can. It cannot move this comparison, because the corpus is
     * validated offline by design. So the finding is declared rather than counted as open work here.
     *
     * Measured 2026-08-31 across the whole corpus: of 71 `Wrong Display Name` findings, 52 name LOINC or
     * SNOMED and carry their own licence reason; the rest name systems that are either unvendored or sit
     * on cases `ComparisonHarness::selectCases()` never selects. Exactly two reach this reason, both on
     * `patient-translated-codes`, and both need language designations rather than a base display.
     */
    public const REASON_HL7_DISPLAY = 'display validation is a terminology-server operation and no terminology client is wired into FHIRValidationService; the corpus is validated offline';

    /**
     * An unresolvable *regular* extension is not an error here, by decision.
     *
     * The reference validator reports `The extension <url> could not be found so is not allowed here`
     * whenever it cannot locate an extension's definition. This project deliberately does not: only
     * *modifier* extensions are enforced, because those change the meaning of the containing resource,
     * while an unrecognised regular extension is ignorable (`FHIRValidationService`, search:
     * `validateModifierExtensions`).
     *
     * Reversing it was measured on 2026-08-31 and rejected: it closes 29 R4 findings but leaves four
     * cases `ABOVE` that no manifest signal can gate, because the reference validator is itself
     * inconsistent here: it reports nothing at all on `http://example.org/additional-information`
     * (`questionnaire-enableWhen-dw`) and `http://acme.com/some_url` (R5 `list-extension`) while
     * erroring on the identical shape in `q-bp`. Matching it would mean reproducing that
     * inconsistency, and over-reporting is the one direction this comparison must never move.
     */
    public const REASON_UNKNOWN_EXTENSION = 'an unresolvable regular extension is not reported, by decision; only modifier extensions are enforced';

    /**
     * An invariant that evaluates to empty is not a failure here, by decision.
     *
     * FHIRPath is three-valued, and an empty result means "unknown". The reference validator turns
     * empty into `false` (`FHIRPathEngine.convertToBoolean`) and reports the invariant as failed. This
     * project does not, because empty arises constantly from valid data: `FHIRPathInvariantValidator`
     * (search: `An empty collection is not a failure`) records the case pair that shows collapsing it
     * would report a Reference the reference validator itself passes.
     *
     * Each case below is empty for a reason that is valid FHIRPath, checked 2026-11-17:
     *
     * - `encounter-period` (per-1): `start` is the date `2023-06-21` and `end` the dateTime
     *   `2023-06-21T06:20:00Z`. Values of different precision that agree as far as both go are not
     *   comparable, so `start <= end` is empty.
     * - `fhirpath-bad` and `fhirpath-null` (ctm-1): `member.resolve() is Practitioner`, where `member`
     *   points at a Practitioner that is not in the document. `resolve()` finds nothing, and `is` on
     *   empty is empty.
     */
    public const REASON_EMPTY_INVARIANT = 'an invariant that evaluates to empty is unknown rather than failed, by decision; the reference validator treats empty as false';

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

            // Every Java error on these three is `could not be found`. Cases that carry one of those
            // findings *beside* something else are deliberately absent: `target-ref-profile-empty`
            // (7 of 8) and `bundle-urn` (1 of 11) stay BELOW on their remaining findings, exactly as
            // the partly-terminology-bound cases do.
            'q-bp'                                 => ['reason' => self::REASON_UNKNOWN_EXTENSION, 'ours' => 0, 'java' => 17],
            'pat-dob-ext'                          => ['reason' => self::REASON_UNKNOWN_EXTENSION, 'ours' => 0, 'java' => 1],
            'nested-questionnaire-nested-valueset' => ['reason' => self::REASON_UNKNOWN_EXTENSION, 'ours' => 0, 'java' => 1],

            'parameters-reference-bad'    => ['reason' => self::REASON_NO_RESOURCE_FETCHER, 'ours' => 0, 'java' => 1],
            'bad-bundle-reference-type-4' => ['reason' => self::REASON_R4_URN_ID_MATCH, 'ours' => 0, 'java' => 1],
            'encounter-period'            => ['reason' => self::REASON_EMPTY_INVARIANT, 'ours' => 0, 'java' => 1],
        ],
        'R4B' => [],
        'R5'  => [
            'observation-cholesterol-good'           => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'observation-triglyceride-good'          => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'observation-triglyceride-good2'         => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'demo-example-2'                         => ['reason' => self::REASON_LOINC, 'ours' => 0, 'java' => 1],
            'contained-canonical'                    => ['reason' => self::REASON_UNKNOWN_EXTENSION, 'ours' => 0, 'java' => 1],
            'reference-bad'                          => ['reason' => self::REASON_NO_RESOURCE_FETCHER, 'ours' => 0, 'java' => 1],
            'fhirpath-bad'                           => ['reason' => self::REASON_EMPTY_INVARIANT, 'ours' => 0, 'java' => 1],
            'fhirpath-null'                          => ['reason' => self::REASON_EMPTY_INVARIANT, 'ours' => 0, 'java' => 1],
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
     * German KBV profiles and value sets, which `obs-de` and `obs-de-notx` load from IG packages by name.
     *
     * The manifest gives those cases `packages` (`kbv.basis#1.2.0`, `kbv.mio.diga#1.0.0-festlegung` and
     * two more) that the reference validator downloads from the package registry. The corpus does not
     * ship them, and the harness validates offline against the core package only, so the profile the
     * instance claims is never found. Both cases' fixed-value finding on `code.coding:loinc.version`
     * (#161) and the `KBV_VS_Base_Body_Weight_Snomed` binding finding stand on that.
     *
     * Loading the packages would not be enough on its own, checked 2026-11-14 against
     * `kbv.mio.diga#1.0.0-festlegung`: its body weight profile ships without a snapshot, which runtime
     * validation requires, and inherits the fixed `version` from `kbv.basis`. Its `code.coding` slicing
     * by pattern on `$this` is matched since the slice's own pattern became its discriminator value. The
     * fixed value on a slice child itself is enforced: `FixedValueValidationTest::testFixedValueInsideASliceIsChecked`.
     */
    public const REASON_UNVENDORED_PACKAGE = 'the IG packages this case loads by name are not vendored, and their profile ships without a snapshot';

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
     * **pinned counts**, so that property is kept: see {@see EXPECTED_FINDING_COUNTS}. A new LOINC finding
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

    /**
     * A profile from a guide the corpus names but does not ship.
     *
     * `no.gastronet` claims `http://kvalitetsregistre.no/fhir/StructureDefinition/colonoscopyreport-bundle-gastronet`,
     * and its manifest entry loads the `colonoscopyreport.no#0.7.4` package for the reference validator. That
     * package is in neither the vendored corpus nor any package this repository carries, so the profile is
     * never resolved here and every finding that comes from it (its required `Bundle.entry` slices, and the
     * `Unable to find a profile match` findings for the entry profiles it names) stays unreachable.
     *
     * Kept apart from {@see REASON_UNVENDORED_IG}, which is about a missing code system rather than a missing
     * profile, so the two can be revisited separately.
     */
    public const REASON_UNVENDORED_PROFILE = 'the implementation guide defining this profile is not vendored, and the corpus names its package without shipping it';

    /** Refusing a DOCTYPE is the right behaviour, so the reference finding behind it is unreachable. */
    public const REASON_XXE_REFUSAL = 'refusing an XML DOCTYPE is deliberate: it blocks external-entity attacks, so this finding is unreachable by design';

    /**
     * A finding the reference validator raises only when told to read a reference's type from its URL.
     *
     * `R4.dr-eh` reports `The type 'Device' implied by the reference URL Device/foo is not a valid Target`.
     * The reference validator raises that only when its manifest sets `assumeValidRestReferences`, which
     * tells it to treat `Type/id` as proof of the target's type without resolving it. This project reads
     * the type from the URL by default (#229), so it reports `Practitioner/2` on an element that allows
     * only `Organization` where the reference validator's default does not (`R5.fhirpath-good`, seeded
     * as ABOVE).
     *
     * `dr-eh` stays unreachable for another reason: the `[Patient]` restriction comes from US Core
     * 3.1.0, which the manifest loads for that case and this harness does not. Against the base
     * `DocumentReference.subject` targets, `Device` is allowed.
     */
    public const REASON_ASSUMED_REST_REFERENCE = 'a reference URL is taken as proof of its target type only under the reference validator option assumeValidRestReferences, which is off by default';

    /**
     * The reference validator fetches a reference's target from outside the instance, and we do not.
     *
     * `parameters-reference-bad` (R4) and `reference-bad` (R5) set `validateReference:
     * CHECK_EXISTS_AND_TYPE` in the manifest, which hands the reference validator a resource fetcher
     * that answers "does `Patient/2` exist?". Neither reference points into a Bundle or a `contained`
     * resource, so nothing in the instance can settle it, and the corpus is validated offline.
     */
    public const REASON_NO_RESOURCE_FETCHER = 'the reference validator looks the target up through a resource fetcher the manifest configures; the corpus is validated offline with none';

    /**
     * An R4-only reference validator behaviour that its own R5 run of the same file does not repeat.
     *
     * `bad-bundle-reference-type.xml` is a `collection` whose Patient refers to
     * `Practitioner/5d9588d8-…` and whose only other entry is an Organization at
     * `urn:uuid:5d9588d8-…`. Under R4 (`bad-bundle-reference-type-4`) the reference validator matches the
     * id against the `urn:uuid:` and reports the type mismatch; under R5 (`-5`) it reports only that the
     * URL cannot be resolved without a RESTful base, at information level. A `collection` is an open
     * envelope this project never resolves (see `BundleReferenceResolutionChecker`), and copying the R4
     * match would mean resolving a relative reference against a `urn:uuid:` in one version only.
     */
    public const REASON_R4_URN_ID_MATCH = 'the R4 reference validator matches a relative id against a urn:uuid fullUrl in a collection, which its R5 run of the same file does not';

    /**
     * The reference validator reports one ambiguous document section entry twice, and we report it once.
     *
     * On `bundle-document-versioned-references-bad` the reference `Observation/ObservationExample`
     * matches two entries. The reference validator reports it as `Found 2 matches for …` from its
     * reference resolution and again as `Multiple matches in bundle for reference …` from its document
     * section check. We report the first, which pairs. The second names the same reference on the same
     * section entry and is the same defect.
     */
    public const REASON_DUPLICATE_REPORT = 'the reference validator reports the same ambiguous section entry a second time from its document section check';

    /**
     * The reference validator's snapshot drops slicing a profile's differential declares on a slice.
     *
     * `R5.reslicing-extensions` slices `AuditEvent.agent.extension` by `url` and declares slicing on
     * its `altid` slice by `url` and `value.system`, which tells `altid/npi` from `altid/ssn`. The
     * snapshot shipped with the case has no slicing on `altid`, so the reference validator tells the
     * two reslices apart by `url` alone, finds both items in both, and reports them. This project
     * follows the differential, as it does for every slicing (#242), so the instance conforms. The
     * case's `reslicing-good-extensions` twin moves the discriminator to the element and passes in both.
     */
    public const REASON_DROPPED_RESLICING = 'the reference validator snapshot drops the slicing the profile declares on a resliced slice; this project follows the differential';

    /**
     * Text signature => reason, for limitations that are not about a code system.
     *
     * Kept apart from {@see TERMINOLOGY_SYSTEMS} because the two are different claims. A terminology entry
     * says "we have no copy of this data"; an entry here says "we decline to do this, and would decline
     * again". `list-xhtml-xxe1` is the whole of it: the reference validator parses a document declaring a
     * DOCTYPE, we refuse, and refusing is correct — `disallow-doctype-decl` is what stops an external-entity
     * attack. Counting that as a gap would put pressure on a security control.
     *
     * One entry is a missing-data claim instead: {@see REASON_UNVENDORED_PROFILE} names a profile, not a
     * code system, so {@see TERMINOLOGY_SYSTEMS} is the wrong home for it, and its signature is the
     * profile's canonical prefix.
     *
     * @var array<string, string>
     */
    public const DECLARED_SIGNATURES = [
        'doctype is disallowed'       => self::REASON_XXE_REFUSAL,
        'found a doctype declaration' => self::REASON_XXE_REFUSAL,
        // Deliberately the full sentence, not 'could not be found'. The short form also matches
        // profile and value-set messages that are ordinary open work, and writing those off is the
        // failure mode this whole class exists to prevent.
        'could not be found so is not allowed here' => self::REASON_UNKNOWN_EXTENSION,
        // Safe to match on the opening phrase alone: it is the literal opening of exactly one reference
        // validator rule and cannot reach a structural finding. Order saves the attribution — the
        // licence-bound systems are matched first in reasonFor(), so a LOINC display keeps REASON_LOINC
        // and only a display on a system with no licence excuse falls through to here.
        'wrong display name' => self::REASON_HL7_DISPLAY,
        // Every profile under this prefix comes from `colonoscopyreport.no`, the package `no.gastronet`
        // loads and the corpus does not ship. Matched on the StructureDefinition prefix, not the domain,
        // so a code system from the same registry would still count as open work.
        'http://kvalitetsregistre.no/fhir/structuredefinition/' => self::REASON_UNVENDORED_PROFILE,
        // The opening of exactly one reference validator rule, raised only under assumeValidRestReferences.
        'implied by the reference url' => self::REASON_ASSUMED_REST_REFERENCE,
        // The host of every KBV canonical. No vendored package defines anything under it, so no
        // finding naming it can be one we could decide.
        'https://fhir.kbv.de/' => self::REASON_UNVENDORED_PACKAGE,
        // The next four are whole findings, quoted in full, so each declares one corpus finding and
        // never the rule behind it: a wrong-type match, an ambiguous section entry or an unresolvable
        // reference anywhere else would still count as open work. See REASON_DUPLICATE_REPORT.
        'multiple matches in bundle for reference observation/observationexample' => self::REASON_DUPLICATE_REPORT,
        // See REASON_R4_URN_ID_MATCH.
        'matching reference for reference practitioner/5d9588d8-3cd6-4b32-8fb7-ad13694e069d has resourcetype organization' => self::REASON_R4_URN_ID_MATCH,
        // See REASON_NO_RESOURCE_FETCHER: `parameters-reference-bad` (R4) and `reference-bad` (R5).
        "unable to resolve resource with reference 'patient/2'"         => self::REASON_NO_RESOURCE_FETCHER,
        "unable to resolve resource with reference 'patient/test-fail'" => self::REASON_NO_RESOURCE_FETCHER,
        // Keyed by invariant, which is the shape that once swallowed a genuine finding, so it is held to
        // two invariants and to the pinned counts in EXPECTED_FINDING_COUNTS. Only an invariant we do not
        // report reaches here, and both evaluate to empty on every corpus case that carries them; a new
        // one moves the pin and has to be read before it is accepted. See REASON_EMPTY_INVARIANT.
        'constraint failed: per-1:' => self::REASON_EMPTY_INVARIANT,
        'constraint failed: ctm-1:' => self::REASON_EMPTY_INVARIANT,
        // The canonical of the one profile concerned, so no other reslicing finding can match it.
        'http://example.org/reslicedextension/structuredefinition/myauditeventprofile' => self::REASON_DROPPED_RESLICING,
    ];

    /**
     * version => reason => how many findings it blocks, as measured 2026-08-20.
     *
     * The property that made the case-keyed {@see MAP} safe, kept for the system-keyed rule: a claim that
     * cannot fail is not worth making. Pinned by
     * `MissingFindingMeasurementTest::testDeclaredLimitationsMatchTheirPinnedCounts()` — not by
     * `DeclaredLimitationsTest`, which pins {@see MAP} instead — so a new LOINC finding has to fail a test
     * rather than quietly join the written-off pile, which is exactly how the invariant-keyed suppression
     * this class replaced went wrong.
     *
     * Update these only after reading why the number moved. Growing means more findings are being written
     * off; shrinking means a limitation stopped being one and its entry should go.
     *
     * @var array<string, array<string, int>>
     */
    public const EXPECTED_FINDING_COUNTS = [
        'R4' => [
            // Order matters: the pin compares with assertSame against a histogram sorted by count
            // descending, then by reason text, so list these largest first and break ties the same way.
            self::REASON_UNKNOWN_EXTENSION      => 30,
            self::REASON_LOINC                  => 27,
            self::REASON_SNOMED                 => 19,
            self::REASON_UNVENDORED_PROFILE     => 6,
            self::REASON_ICD10                  => 4,
            self::REASON_CVX                    => 3,
            self::REASON_HL7_DISPLAY            => 2,
            self::REASON_UNVENDORED_PACKAGE     => 2,
            // Tied at one: ordered by reason text, as in R5 below.
            self::REASON_ASSUMED_REST_REFERENCE => 1,
            self::REASON_EMPTY_INVARIANT        => 1,
            self::REASON_NCI                    => 1,
            self::REASON_R4_URN_ID_MATCH        => 1,
            self::REASON_NO_RESOURCE_FETCHER    => 1,
            self::REASON_DUPLICATE_REPORT       => 1,
        ],
        'R4B' => [],
        'R5'  => [
            self::REASON_LOINC             => 7,
            self::REASON_EMPTY_INVARIANT   => 2,
            // These two are tied at one, so their order is decided by reason text rather than by
            // whatever order the corpus produced them in. See ComparisonReport::declaredByReason().
            self::REASON_UNKNOWN_EXTENSION   => 1,
            self::REASON_XXE_REFUSAL         => 1,
            self::REASON_NO_RESOURCE_FETCHER => 1,
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
