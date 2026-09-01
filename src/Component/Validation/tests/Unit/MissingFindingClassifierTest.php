<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\JavaOutcome;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\MissingFindingClassifier;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Pins the capability labels the parity work picks its next target from.
 *
 * Reference texts are copied verbatim from vendored outcome files. The label is a claim about *what would
 * have to be built*, so a mislabel sends work at the wrong capability — which is not a cosmetic error:
 * `bundle:resolve` needs reference resolution across a Bundle, while `bundle:fullurl` needs a URL check on
 * one element, and the two are days apart in effort.
 */
final class MissingFindingClassifierTest extends TestCase
{
    /**
     * Reference finding => the capability it needs.
     *
     * @return iterable<string, array{string, string}>
     */
    public static function labelledFindings(): iterable
    {
        yield 'display name needs a code system' => [
            'Wrong Display Name \'Hemoglobin\' for http://loinc.org#718-7. Valid display is one of 3 choices',
            'terminology:display',
        ];

        yield 'unknown code needs a code system' => [
            "Unknown code 'XYZ' in the CodeSystem 'http://snomed.info/sct' version '20240101'",
            'terminology:code',
        ];

        yield 'value set membership needs expansion' => [
            "The value provided ('foo') was not found in the value set 'AdministrativeGender'",
            'terminology:valueset',
        ];

        yield 'reachability needs graph traversal' => [
            "Entry 'urn:uuid:1' isn't reachable by traversing links (forward or backward) from the Composition",
            'bundle:reachability',
        ];

        yield 'a bare fullUrl rule is a URL check' => [
            "The fullUrl must be an absolute URL (not '1fe46da3')",
            'bundle:fullurl',
        ];

        yield 'primitive format needs a stricter reader' => [
            "Not a valid instant format: '1983-00-01T12:32:45Z'",
            'primitive:format',
        ];

        yield 'narrative rules need XHTML parsing' => [
            "Hyperlink scheme 'javascript' in 'x' at 'y' for 'z' is not a valid hyperlinkable scheme",
            'narrative:xhtml',
        ];

        // Needs the binding *strength*, which generated attributes carry, and nothing from the value set.
        yield 'a required binding with no code is not a terminology lookup' => [
            'No code provided, and a code is required from the value set',
            'binding:required',
        ];

        yield 'an unevaluated invariant is named by its key' => [
            "Constraint failed: bdl-7: 'FullUrl must be unique in a bundle'",
            'invariant:unevaluated',
        ];
    }

    /** Each reference wording is attributed to the capability that would have to be built. */
    #[DataProvider('labelledFindings')]
    public function testAFindingIsLabelledWithTheCapabilityItNeeds(string $javaText, string $expected): void
    {
        self::assertSame($expected, (new MissingFindingClassifier())->classify($javaText));
    }

    /**
     * The mislabel this ordering was introduced to fix.
     *
     * Java explains a failed reference by describing the fullUrl rules that defeated it, so the text
     * mentions `fullUrl` while the missing capability is reference resolution. A substring test for
     * `fullurl` run first captured seven such findings on `R4.bundle-urn` and sent them at the wrong
     * capability. Text copied from `outcomes/java/R4.bundle-urn-base.json`.
     */
    public function testAResolutionFailureMentioningFullUrlIsNotLabelledAsAFullUrlRule(): void
    {
        $java = "Can't find 'Patient/98549f1a' in the bundle (Composition.subject). Note that there is a "
            . 'resource in the bundle with the same type and id, but it does not match because of the '
            . "fullUrl based rules around matching relative references. Found 'urn:uuid:98549f1a'";

        self::assertSame('bundle:resolve', (new MissingFindingClassifier())->classify($java));
    }

    /**
     * The third instance of a broad signature stealing from a narrow one.
     *
     * `profile:structure` carries `is not valid` as a catch-all for profile complaints, which also matches
     * the primitive rule about identifier characters. Ordering primitives first fixes it; without this
     * test nothing would notice the next time the blocks move, and the label is what M02 picks work from.
     */
    public function testAnInvalidIdIsAPrimitiveRuleNotAProfileRule(): void
    {
        $java = "id value 'a-very-long-id-with-invalid-characters!' is not valid";

        self::assertSame('primitive:format', (new MissingFindingClassifier())->classify($java));
    }

    /**
     * Every finding whose text matches more than one label, with the label that must win.
     *
     * Measured by matching all 1313 reference error texts against every signature rather than stopping at
     * the first hit: the corpus holds **eleven** distinct label contests. Five of the eleven occur only on
     * cases carrying no committed fixture, so nothing compares them and no ordering decision reaches them.
     * The remaining six are pinned here and by the two standalone tests above.
     *
     * An earlier version of this docblock claimed seven collisions and called them complete, which was
     * wrong in both directions: the provider held four entries, and two of the six pins cover the *same*
     * contest (`primitive:format BEATS profile:structure` — the invalid-id test above and the whitespace
     * case below). Counting tests is not counting contests, which is how `invariant:unevaluated BEATS
     * bundle:fullurl` went unpinned while being the one mislabel {@see MissingFindingClassifier::SIGNATURES}
     * names as its own reason for ordering invariants first.
     *
     * Texts are verbatim from `vendor/fhir/fhir-test-cases/validator/outcomes/java/`.
     *
     * @return iterable<string, array{string, string}>
     */
    public static function orderSensitiveFindings(): iterable
    {
        // An invariant that mentions enableWhen still needs invariant evaluation, not questionnaire rules.
        yield 'invariant beats questionnaire rules' => [
            "Constraint failed: que-12: 'If there are more than one enableWhen, enableBehavior must be specified'",
            'invariant:unevaluated',
        ];

        // `profile:extension` carries the broad `could not be found`, which this text contains further in
        // ("A definition for CodeSystem … could not be found"). The obstacle is terminology, not an
        // extension definition, so terminology must be ordered first.
        yield 'value set membership beats a missing definition' => [
            'The code provided (http://fhir.mimic.mit.edu/CodeSystem/admission-class#URGENT) was not found '
            . "in the value set 'Admission Class' (http://mimic.fhir.mit.edu/ValueSet/admission-class|0.1.2), "
            . 'and a code from this value set is required: A definition for CodeSystem '
            . "'http://fhir.mimic.mit.edu/CodeSystem/admission-class' could not be found, so the code cannot "
            . 'be validated',
            'terminology:valueset',
        ];

        // Reads like terminology because the subject is a code, but deciding it needs no code system at
        // all — only the string. It moved out of `terminology:code` when M03's licence split asked which
        // system it needed and the answer was none. `profile:structure`'s broad `is not valid` still
        // matches the text, so the placement is load-bearing.
        yield 'a whitespace rule on a code is a string check' => [
            "The code ' asdasd' is not valid (whitespace rules)",
            'primitive:format',
        ];

        // The mislabel the SIGNATURES comment cites as its reason for ordering invariants first, and the
        // only contest on a compared case that nothing pinned until now. `bdl-7`'s description mentions
        // fullUrl, so `bundle:fullurl` — a single broad `fullurl` substring — also matches it. The
        // capability an unevaluated invariant needs is invariant evaluation, whatever its description
        // happens to mention. Live on `japanese-utf8-ok`, which is compared.
        yield 'an invariant beats a bundle rule its description mentions' => [
            "Constraint failed: bdl-7: 'FullUrl must be unique in a bundle, or else entries with the same "
            . "fullUrl must have different meta.versionId (except in history bundles)'",
            'invariant:unevaluated',
        ];

        // Both labels are terminology and both are blocked by the same accepted decision, so the split
        // does not change what can be closed. Pinned anyway, because it is decided by order alone and a
        // silent flip would move findings between capability totals for no reason.
        yield 'an unknown code beats value set membership' => [
            'The code provided (http://fhir.de/CodeSystem/dimdi/icd-10-gm#A54.4 M73.04) was not found in the '
            . "value set 'ICD10GM': Unknown code 'A54.4 M73.04' in the CodeSystem "
            . "'http://fhir.de/CodeSystem/dimdi/icd-10-gm' version '2020'",
            'terminology:code',
        ];
    }

    /** A finding matching several labels is decided by order, so the winner is pinned. */
    #[DataProvider('orderSensitiveFindings')]
    public function testAnAmbiguousFindingGoesToTheLabelThatOwnsIt(string $javaText, string $expected): void
    {
        self::assertSame($expected, (new MissingFindingClassifier())->classify($javaText));
    }

    /**
     * The corpus can state an error count with no message, and that is not the same as an unknown rule.
     *
     * `other` means "we read the rule and it fits no capability"; this means "there is nothing to read".
     * Folding the second into the first would make the unowned bucket look like a classifier gap.
     */
    public function testACountedErrorWithNoMessageGetsItsOwnLabel(): void
    {
        $label = (new MissingFindingClassifier())->classify(JavaOutcome::TEXT_UNAVAILABLE);

        self::assertSame('unclassifiable:no-message', $label);
        self::assertNotSame(MissingFindingClassifier::OTHER, $label);
    }

    /**
     * Labels must partition, so the breakdown can be trusted to sum to the total.
     *
     * A classifier that dropped an unrecognised finding would understate the very gap it measures, so
     * anything unmatched lands in `other` and stays counted.
     */
    public function testAnUnrecognisedFindingIsCountedRatherThanDropped(): void
    {
        $labels = (new MissingFindingClassifier())->classifyAll([
            'Something the reference validator has never said before',
            "Unknown code 'X' in the CodeSystem 'Y'",
        ]);

        self::assertSame([MissingFindingClassifier::OTHER, 'terminology:code'], $labels);
    }

    /** One label per finding, so a histogram over them sums to the finding total. */
    public function testEveryFindingGetsExactlyOneLabel(): void
    {
        $texts  = ['Wrong Display Name for x', 'nothing recognisable', "Constraint failed: bdl-1: 'x'"];
        $labels = (new MissingFindingClassifier())->classifyAll($texts);

        self::assertCount(count($texts), $labels);
    }
}
