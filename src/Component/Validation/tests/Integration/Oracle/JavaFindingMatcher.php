<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;

/**
 * Decides, per reference-validator finding, whether we report the same thing in different words.
 *
 * ## Why counting errors is not enough
 *
 * {@see Classification} compares two integers, so a case lands in `BELOW` whenever Java reports more
 * errors than we do. That is not the same as "we are missing that many checks". `R4.Observation-ex-pain`
 * is the proof: Java reports `Observation.code: minimum required = 1, but only found 0` and
 * `The property 'value' is invalid`, we report `This value should not be blank.` on path `code`. Our
 * finding *is* Java's first one. The case is one finding short, not two — and 37 of the 168 `BELOW`
 * cases across R4 and R5 report at least one error from us, so the case count systematically overstates
 * the missing-check surface. Sizing work off it would fabricate the denominator that every later
 * "we closed N" claim is measured against.
 *
 * ## Precision over recall, deliberately
 *
 * The only failure that matters here is a **false pair**: claiming we already report something we do
 * not, which erases a real gap and can never be detected downstream. Leaving a genuine pair unmatched
 * merely overstates the gap, which the next audit catches. So every rule below demands positive
 * evidence that both sides name the same rule, and anything uncertain stays unmatched.
 *
 * **There is deliberately no path-only rule.** Two validators disagreeing about `Patient.name` may well
 * be reporting different rules on the same element, and pairing them on the path alone would be exactly
 * the invisible false pair this class exists to avoid.
 *
 * A "one side quotes the other" rule was tried and removed: across all three versions it claimed **zero**
 * pairings, because Java's `Constraint failed: <key>: '<description>'` wrapper is already paired by
 * invariant key. A rule that never fires cannot be audited, so it is a latent false-pair source earning
 * nothing. Do not reinstate it without a case it actually pairs.
 *
 * Each of our findings can satisfy at most one reference finding and vice versa, so a case reporting one
 * error can never explain away two of Java's.
 */
final class JavaFindingMatcher
{
    /**
     * Marks one of our findings as "we refused to read this document at all".
     *
     * Set by {@see ComparisonHarness} on the finding it synthesises from a read failure. Needed because
     * such a finding has no path, no constraint and no invariant key, so nothing else about it says which
     * kind of finding it is.
     */
    public const UNREADABLE_DOCUMENT_CODE = 'oracle:unreadable-document';

    /**
     * Phrases identifying a reference finding as a complaint about reading the bytes.
     *
     * The one place a single finding of ours legitimately explains several of Java's: a document is
     * unreadable once, but the reference validator reports each place it noticed — `json-no-quotes-2`
     * yields three, `json-comma-bad-2` three more. Pairing those one-to-one would score the surplus as
     * checks we lack while we already reject the whole file, which is the inflation this class exists to
     * remove.
     *
     * Only unambiguous parse and encoding diagnostics belong here. `R5.observation-with-trailing-dot`
     * holds `"value": 925.`, which our JSON reader rejects outright while the reference validator reads it
     * and reports `The value '925.' is not a valid decimal` — the same document defect, so pairing is
     * tempting. It is excluded, because that phrasing is indistinguishable from the *validation* rule of
     * the same name, which we genuinely do not implement. The {@see refusedToRead()} guard is not enough
     * to make a validation-shaped signature safe: `R4.japanese-utf8-ok` proves refusing to read a file
     * does not mean Java refused it too — there Java read the document and reported 108 ordinary
     * validation findings, every one of which a loose signature could pair away. The cost of excluding it
     * is overstating the gap by one finding, which is the direction that stays honest.
     *
     * @var list<string>
     */
    private const READ_FAILURE_SIGNATURES = [
        'error parsing json',
        'expected one of',
        'a comma is missing',
        'has no quotes around',
        'comments are not allowed in json',
        'unable to parse',
        'content is not allowed in prolog',
        'is not proper utf-8',
        'premature end of file',
        'unexpected close marker',
        'extra trailing comma',
        'extra comma at the end',
        'must be a json',
        'unexpected char',
        'illegal character',
        'must be terminated by the matching end-tag',
        'was referenced, but not declared',
        'invalid xml character',
        'saxexception',
    ];

    /**
     * Families whose violation means "this element is absent or repeats too often".
     *
     * Java words that as `minimum required = N` / `max allowed = N`; Symfony words the same finding as a
     * blank, null or count failure, so the texts never resemble each other and only the rule kind pairs
     * them.
     *
     * A cardinality breach caught at read time rather than at validation time needs no entry here.
     * `bundle-dual-subject` is the case: we reject it while deserializing with
     * `Composition.subject: max allowed = 1, but found 2`, which is Java's sentence verbatim once
     * {@see normalise()} drops the trailing `(from http://…)` provenance, so `same-text` pairs it first.
     * An earlier draft listed `conformance:deserialization` here for that case; it could never match,
     * because a synthesised read-time finding carries no constraint class and classifies as `other`.
     *
     * @var list<string>
     */
    private const CARDINALITY_FAMILIES = [
        'constraint:NotBlank',
        'constraint:NotNull',
        'constraint:Count',
        'constraint:Length',
    ];

    /**
     * Phrases both validators open with when they report the same rule in their own words.
     *
     * A closed table, deliberately. Our `Regex` message and the reference validator's are unrecognisable
     * to {@see matchesByText()} — `Invalid Resource id: "/foobar==" must be 1-64 characters of A-Z, a-z,
     * 0-9, "-" or "."` against `Invalid Resource id: Invalid Characters ('/foobar==')` — yet they are one
     * finding, and `resource-invalid-id-1` reads `ours 1, java 1` while contributing 1 to the missing
     * total. Nothing generalises here: a phrase earns its place by being distinctive enough that two
     * validators using it are necessarily talking about the same rule.
     *
     * Adding a phrase widens what can be paired away, so each one needs its own audited before-and-after.
     *
     * @var list<string>
     */
    private const SHARED_PHRASES = [
        'invalid resource id:',
    ];

    /**
     * The clause that commits either validator to the Bundle fullUrl matching rules.
     *
     * Deliberately not `in the bundle`, which the neighbouring rules also use: `Found 2 matches for
     * 'X' in the bundle` is ambiguity and `Can't find 'X' in the bundle` alone is plain absence, and
     * pairing this family against either would credit us with a rule we have not written. This clause
     * appears in all three of the reference validator's sentence templates for the rule and in neither
     * of the others. See {@see matchesByBundleResolution()}.
     */
    private const string BUNDLE_RESOLUTION_PHRASE = 'fullurl based rules';

    /**
     * Our invariant key => the reference validator's paraphrases of it, when it never cites the key.
     *
     * `ele-1` is the case: on `group-choice-empty` and `list-empty1` we report
     * `All FHIR elements must have a @value or children` and the reference validator reports
     * `Element must have some content`, with no `Constraint failed:` wrapper for
     * {@see matchesByInvariantKey()} to work from. Keyed by invariant so a paraphrase can only ever pair
     * with the one rule it paraphrases.
     *
     * `Array cannot be empty` is deliberately absent. It is the same defect, but on `empty-array` the
     * reference validator points one level deeper than we do — `DocumentReference.category[0].coding`
     * against our `category[0]` — so pairing it needs a prefix comparison rather than an equality one.
     * One overstated finding is cheaper than a rule that pairs on containment.
     *
     * @var array<string, list<string>>
     */
    private const INVARIANT_PARAPHRASES = [
        'ele-1' => ['element must have some content'],
    ];

    /**
     * Reference findings for each of our findings that reports the same rule.
     *
     * @param list<string>                  $javaErrorTexts  reference-validator error-severity texts
     * @param list<FHIRValidationViolation> $ourErrors       our error-severity violations for the same case
     * @param list<string>                  $javaExpressions where each reference finding was found,
     *                                                       parallel to $javaErrorTexts. Cardinality
     *                                                       pairing needs it and refuses without it;
     *                                                       see {@see matchesByCardinality()}
     *
     * @return list<string> the reference findings left with no counterpart, in their original order
     */
    public function unmatched(array $javaErrorTexts, array $ourErrors, array $javaExpressions = []): array
    {
        return $this->pair($javaErrorTexts, $ourErrors, $javaExpressions)['unmatched'];
    }

    /**
     * Every pairing this matcher claims, for review by eye.
     *
     * The audit surface. A false pair is invisible in {@see unmatched()} by definition — the finding it
     * erased simply is not in the list — so the only way to check precision is to read what was paired
     * and why.
     *
     * @param list<string>                  $javaErrorTexts
     * @param list<string>                  $javaExpressions parallel to $javaErrorTexts
     * @param list<FHIRValidationViolation> $ourErrors
     *
     * @return list<array{java: string, ours: string, ourPath: string, rule: string}> the reference
     *                                                                                finding, ours, where
     *                                                                                ours was reported,
     *                                                                                and the rule that
     *                                                                                paired them
     */
    public function matchedPairs(array $javaErrorTexts, array $ourErrors, array $javaExpressions = []): array
    {
        return $this->pair($javaErrorTexts, $ourErrors, $javaExpressions)['matched'];
    }

    /**
     * Walk both sides once, recording both what paired and what did not.
     *
     * One pass rather than two so the audit view and the count can never disagree about the same case.
     *
     * @param list<string>                  $javaErrorTexts
     * @param list<FHIRValidationViolation> $ourErrors
     * @param list<string>                  $javaExpressions parallel to $javaErrorTexts
     *
     * @return array{matched: list<array{java: string, ours: string, ourPath: string, rule: string}>, unmatched: list<string>}
     */
    private function pair(array $javaErrorTexts, array $ourErrors, array $javaExpressions = []): array
    {
        $available   = array_keys($ourErrors);
        $unmatched   = [];
        $matched     = [];
        $weRefusedIt = $this->refusedToRead($ourErrors);

        foreach ($javaErrorTexts as $index => $javaText) {
            if ($weRefusedIt && $this->isReadFailure($javaText)) {
                $matched[] = [
                    'java'    => $javaText,
                    'ours'    => 'we refused to read the document',
                    'ourPath' => '',
                    'rule'    => 'read-failure',
                ];
                continue;
            }

            $found = null;
            $rule  = null;

            foreach ($available as $position => $ourIndex) {
                $rule = $this->pairingRule($javaText, $ourErrors[$ourIndex], $javaExpressions[$index] ?? '');
                if ($rule !== null) {
                    $found = $position;
                    break;
                }
            }

            if ($found === null) {
                $unmatched[] = $javaText;
                continue;
            }

            $ourViolation = $ourErrors[$available[$found]];
            $matched[]    = [
                'java'    => $javaText,
                'ours'    => $ourViolation->message,
                'ourPath' => $ourViolation->path,
                'rule'    => (string) $rule,
            ];

            // One of ours cannot explain two of Java's.
            unset($available[$found]);
        }

        return ['matched' => $matched, 'unmatched' => $unmatched];
    }

    /**
     * Whether we rejected this document before validating it.
     *
     * @param list<FHIRValidationViolation> $ourErrors
     */
    private function refusedToRead(array $ourErrors): bool
    {
        foreach ($ourErrors as $violation) {
            if ($violation->code === self::UNREADABLE_DOCUMENT_CODE) {
                return true;
            }
        }

        return false;
    }

    /** Whether this reference finding is a complaint about the bytes rather than about the resource. */
    private function isReadFailure(string $javaText): bool
    {
        $haystack = strtolower($javaText);

        foreach (self::READ_FAILURE_SIGNATURES as $signature) {
            if (str_contains($haystack, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Which rule pairs these two findings, or null if none does.
     *
     * Rules are tried strongest-first so the cheapest confident answer wins; every one of them requires a
     * shared rule identity, never a shared location. Returning the rule name rather than a boolean is
     * what makes {@see matchedPairs()} auditable: "these were paired" is not reviewable, "these were
     * paired because both name invariant ref-1" is.
     */
    private function pairingRule(
        string $javaText,
        FHIRValidationViolation $ours,
        string $javaExpression,
    ): ?string {
        return match (true) {
            $this->matchesByInvariantKey($javaText, $ours)                         => 'invariant-key',
            $this->matchesByText($javaText, $ours)                                 => 'same-text',
            $this->matchesByCardinality($javaText, $ours, $javaExpression)         => 'cardinality',
            $this->matchesBySharedPhrase($javaText, $ours, $javaExpression)        => 'shared-phrase',
            $this->matchesByBundleResolution($javaText, $ours)                     => 'bundle-resolution',
            $this->matchesByInvariantParaphrase($javaText, $ours, $javaExpression) => 'invariant-paraphrase',
            default                                                                => null,
        };
    }

    /**
     * The reference validator names the invariant it failed, and we failed the same one.
     *
     * The strongest signal available: `Constraint failed: ref-1: '…'` versus our `invariantKey`. Both
     * sides take the key from the same StructureDefinition, so agreement is identity rather than
     * resemblance.
     */
    private function matchesByInvariantKey(string $javaText, FHIRValidationViolation $ours): bool
    {
        if ($ours->invariantKey === null || $ours->invariantKey === '') {
            return false;
        }

        if (preg_match('/Constraint failed:\s*([A-Za-z][\w-]*)\s*:/', $javaText, $m) !== 1) {
            return false;
        }

        return strcasecmp($m[1], $ours->invariantKey) === 0;
    }

    /** Both sides render the same sentence, give or take punctuation and quoted values. */
    private function matchesByText(string $javaText, FHIRValidationViolation $ours): bool
    {
        return $this->normalise($javaText) === $this->normalise($ours->message);
    }

    /**
     * Both sides say the same element, on the same instance, is missing or repeats too often.
     *
     * The one rule where the texts share no wording at all, so it needs three independent agreements: the
     * rule kind (a cardinality family on our side, `minimum required` / `max allowed` on Java's), the
     * element, and the instance the element belongs to.
     *
     * The message alone cannot supply the third. Java names the element by type — `List.status` — so a
     * document holding two Lists that both lack `status` produces two identical messages, and matching on
     * the element name alone pairs them in whatever order they arrive. The aggregate stays right and the
     * attribution is a coin toss, which is invisible afterwards. `issue.expression` supplies what the
     * message does not: for `bundle-ea-testcase` it reads
     * `Bundle.entry[1].resource/*MeasureReport/…*​/.contained[0]/*Bundle/…*​/.entry[0].resource/*List/…*​/`,
     * which is our own `entry[1].resource.contained[0].entry[0].resource` once the `/*Type/id*​/` comments
     * and the root type are removed.
     *
     * With no expression there is no third agreement, so there is no pair. All 108 cardinality findings in
     * the vendored corpus carry one, so this refusal costs nothing today and stops the rule quietly
     * reverting to name-only matching if a future outcome omits it.
     */
    private function matchesByCardinality(string $javaText, FHIRValidationViolation $ours, string $javaExpression): bool
    {
        if (preg_match('/^(?<path>[^:]+):\s*(minimum required|max allowed)\s*=/', $javaText, $m) !== 1) {
            return false;
        }

        $family = (new ViolationFamilyClassifier())->classify($ours);
        if (!in_array($family, self::CARDINALITY_FAMILIES, true)) {
            return false;
        }

        $javaLeaf = $this->leaf($m['path']);
        $ourLeaf  = $this->leaf($ours->path);
        if ($javaLeaf === '' || strcasecmp($javaLeaf, $ourLeaf) !== 0) {
            return false;
        }

        if ($javaExpression === '') {
            return false;
        }

        return $this->instancePath($javaExpression) === $this->parentPath($ours->path);
    }

    /**
     * The instance an expression points at, in the form our own paths take.
     *
     * Java roots at the resource type and annotates each step with the type and id it resolved to; we root
     * inside the resource and annotate nothing. Removing both leaves the same string.
     */
    private function instancePath(string $expression): string
    {
        // Drop the `/*Type/id*/` annotations Java interleaves through the path.
        $path = (string) preg_replace('#/\*.*?\*/#', '', $expression);

        // XML validation reports XPath rather than FHIRPath — `/f:Group/f:characteristic/f:code`. Same
        // path, different notation, so normalise it to the dotted form the rest of this class compares.
        if (str_starts_with($path, '/')) {
            $path = str_replace('/', '.', (string) preg_replace('#/[A-Za-z0-9]+:#', '/', $path));
        }

        $path = trim($path, '.');

        // Drop the leading resource type, which our paths do not carry.
        $dot = strpos($path, '.');

        return $dot === false ? '' : substr($path, $dot + 1);
    }

    /** Everything above the element a violation was reported on. */
    private function parentPath(string $path): string
    {
        $dot = strrpos($path, '.');

        return $dot === false ? '' : substr($path, 0, $dot);
    }

    /**
     * Both sides say the same reference fails the Bundle fullUrl matching rules.
     *
     * This family needs its own rule because neither general route reaches it.
     *
     * {@see matchesByText()} cannot: the reference validator's sentence carries an unquoted context
     * label (`Section Entry`, `MessageHeader Data`, `Composition.subject`) which survives
     * {@see normalise()} and which we would have to reproduce from a lookup table fitted to seven
     * corpus cases. Fitting our own message text to the oracle is the same move as reseeding an outcome
     * to go green, so our wording is our own.
     *
     * {@see matchesBySharedPhrase()} cannot either, because its path agreement is unavailable: **the
     * reference validator's `expression` is off by one for this family.** On `bundle-urn` it reports
     * `Bundle.entry[1].resource.subject` for a reference that lives on the Composition at `entry[0]`;
     * `entry[1]` is the Patient, which has no `subject` at all. The same holds on every case: the
     * carrying resource is `entry[0]` in all seven, and every expression names `entry[1]`. It is
     * specific to these bundle-level rules: the structural findings in the same outcome file are
     * correctly zero-based, which is why `matchesBySharedPhrase()` can trust the expression and this
     * cannot. Three of `bundle-urn`'s findings carry the bare expression `Bundle`, which carries no
     * path at all.
     *
     * So this rule reads the field the reference validator gets **right**, the context label inside the
     * message, and ignores the one it gets wrong. Three agreements where the label is a path:
     *
     *  1. the rule, via a phrase that commits either validator to fullUrl matching rather than to plain
     *     absence (`Can't find …` alone) or ambiguity (`Found 2 matches …`);
     *  2. the reference being resolved, quoted identically by both sides;
     *  3. the element, taken as the label's trailing property name. `Bundle.entry[2].resource.subject`
     *     and `Composition.author` both yield the property, which is what separates `bundle-urn`'s four
     *     findings that all name `Patient/98549f1a-…`.
     *
     * `Section Entry` and `MessageHeader Data` are the reference validator's own names for spec slots
     * and yield no property, so those pair on the first two agreements only. That is a deliberate,
     * narrower guarantee, and it is safe on today's corpus because every finding wearing those two
     * labels names a distinct reference within its case. Should a case ever carry two `Section Entry`
     * findings for one reference, they would pair in arbitrary order, the same exposure
     * {@see matchesByCardinality()} refuses, recorded here rather than hidden.
     */
    private function matchesByBundleResolution(string $javaText, FHIRValidationViolation $ours): bool
    {
        $java = strtolower($javaText);
        $mine = strtolower($ours->message);

        if (!str_contains($java, self::BUNDLE_RESOLUTION_PHRASE) || !str_contains($mine, self::BUNDLE_RESOLUTION_PHRASE)) {
            return false;
        }

        // Agreement 2: the reference itself, which both sides quote first.
        if (preg_match("/can't find '([^']*)'/", $java, $javaRef) !== 1) {
            return false;
        }

        if (preg_match("/can't find '([^']*)'/", $mine, $ourRef) !== 1 || $javaRef[1] !== $ourRef[1]) {
            return false;
        }

        // Agreement 3: the element, from the context label where that label is a path.
        if (preg_match('/in the bundle \(([^)]*)\)/', $java, $context) !== 1) {
            return false;
        }

        // Where the label is bundle-rooted it carries the entry index too, which is a whole path and
        // the strongest form of this agreement. `bundle-urn` needs it: three of its findings name the
        // same `Patient/98549f1a-…` and all three end in `subject`, so a trailing-property comparison
        // pairs them in whatever order they arrive. Comparing the full path pins each to its entry.
        if (str_starts_with($context[1], 'Bundle.')) {
            return substr($context[1], strlen('Bundle.')) === $this->withoutTrailingIndex($ours->path);
        }

        $property = $this->contextProperty($context[1]);

        if ($property === null) {
            return true;
        }

        $tail = $this->withoutTrailingIndex($ours->path);
        $dot  = strrpos($tail, '.');

        return ($dot === false ? $tail : substr($tail, $dot + 1)) === $property;
    }

    /**
     * Our path indexes repeating elements such as `author[0]` while the context label never does, so
     * the index is dropped on our side rather than invented on the reference validator's.
     */
    private function withoutTrailingIndex(string $path): string
    {
        return (string) preg_replace('/\[\d+\]\z/', '', $path);
    }

    /**
     * The trailing property of a context label, or null when the label is a friendly name.
     *
     * `Bundle.entry[2].resource.subject` and `Composition.author` yield `subject` and `author`;
     * `Section Entry` and `MessageHeader Data` contain a space and name no element, so they yield null
     * and the caller falls back to two agreements.
     */
    private function contextProperty(string $context): ?string
    {
        if ($context === '' || str_contains($context, ' ')) {
            return null;
        }

        $dot = strrpos($context, '.');

        if ($dot === false) {
            return null;
        }

        $property = substr($context, $dot + 1);

        return preg_match('/\A[A-Za-z]+\z/', $property) === 1 ? $property : null;
    }

    /**
     * Both sides open with the same distinctive phrase, about the same element.
     *
     * Two agreements, and the phrase carries most of the weight: a table entry is only admitted when using
     * it commits a validator to one particular rule. The element agreement is on the **full** path, not the
     * parent, because here the reference validator's expression names the element itself — `Location.id`,
     * and `Condition.contained[0]/*Practitioner/…*​/.id` against our `contained[0].id`. That is the opposite
     * of {@see matchesByCardinality()}, where the message names the element and the expression names its
     * container.
     */
    private function matchesBySharedPhrase(
        string $javaText,
        FHIRValidationViolation $ours,
        string $javaExpression,
    ): bool {
        if ($javaExpression === '') {
            return false;
        }

        $java = strtolower($javaText);
        $mine = strtolower($ours->message);

        $shared = false;
        foreach (self::SHARED_PHRASES as $phrase) {
            if (str_contains($java, $phrase) && str_contains($mine, $phrase)) {
                $shared = true;
                break;
            }
        }

        if (!$shared) {
            return false;
        }

        return $this->instancePath($javaExpression) === $ours->path;
    }

    /**
     * We failed a named invariant and the reference validator described it without naming it.
     *
     * Keyed by our invariant key, so a paraphrase can only pair with the rule it paraphrases — the table
     * cannot drift into a general synonym list.
     *
     * The element comparison relaxes in exactly one circumstance. These findings arrive from XML
     * validation, where the expression is XPath — `/f:Group/f:characteristic/f:code` — which carries no
     * indexes at all. Requiring an index match against our `characteristic[0].code` would fail on
     * information the source does not contain, so indexes are stripped from both sides **only** for that
     * form. A FHIRPath expression still has to match exactly, indexes included. Relax as far as the source
     * forces and no further.
     */
    private function matchesByInvariantParaphrase(
        string $javaText,
        FHIRValidationViolation $ours,
        string $javaExpression,
    ): bool {
        if ($javaExpression === '' || $ours->invariantKey === null) {
            return false;
        }

        $paraphrases = self::INVARIANT_PARAPHRASES[strtolower($ours->invariantKey)] ?? null;
        if ($paraphrases === null) {
            return false;
        }

        $java = strtolower($javaText);
        $said = false;
        foreach ($paraphrases as $paraphrase) {
            if (str_contains($java, $paraphrase)) {
                $said = true;
                break;
            }
        }

        if (!$said) {
            return false;
        }

        $isXPath   = str_starts_with($javaExpression, '/');
        $javaPath  = $this->instancePath($javaExpression);
        $ourPath   = $ours->path;

        if ($isXPath) {
            $javaPath = $this->withoutIndexes($javaPath);
            $ourPath  = $this->withoutIndexes($ourPath);
        }

        return $javaPath === $ourPath;
    }

    /** A path with every repeat index removed, for comparing against a source that carries none. */
    private function withoutIndexes(string $path): string
    {
        return (string) preg_replace('/\[\d+\]/', '', $path);
    }

    /**
     * The final property name in a FHIR path, with repeat indexes and choice-type suffixes removed.
     *
     * `value[x]` and `valueQuantity` are the same element named two ways, so the `[x]` marker is
     * dropped rather than treated as part of the name.
     */
    private function leaf(string $path): string
    {
        $trimmed = str_replace('[x]', '', $path);
        $trimmed = (string) preg_replace('/\[\d+\]/', '', $trimmed);
        $parts   = array_values(array_filter(explode('.', $trimmed), static fn (string $p): bool => $p !== ''));

        return $parts === [] ? '' : (string) end($parts);
    }

    /**
     * Reduce a message to its rule wording so two renderings of one finding compare equal.
     *
     * Quoted values are emptied rather than removed: `Unknown code 'A'` and `Unknown code 'B'` are the
     * same rule on different data, while dropping the quotes entirely would also erase the difference
     * between `'X' is not valid` and `is not valid`. Trailing provenance (`(from http://…)`,
     * `(defined in http://…)`) is boilerplate only Java emits.
     */
    private function normalise(string $text): string
    {
        $out = strtolower($text);
        $out = (string) preg_replace("/'[^']*'/", "''", $out);
        $out = (string) preg_replace('/"[^"]*"/', '""', $out);
        $out = (string) preg_replace('/\((?:from|defined in|url:|ids:)[^)]*\)/', '', $out);
        $out = (string) preg_replace('/\s+/', ' ', $out);

        return trim($out, " \t\n\r.;:");
    }
}
