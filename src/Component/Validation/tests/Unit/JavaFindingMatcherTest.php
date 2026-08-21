<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\JavaFindingMatcher;
use PHPUnit\Framework\TestCase;

/**
 * Pins the pairing rules that turn a case count into a finding count.
 *
 * Every reference text below is copied verbatim from a vendored outcome file under
 * `vendor/fhir/fhir-test-cases/validator/outcomes/java/`, and every message of ours from the matching
 * seed under `outcomes/ardenexal/`. Inventing either would let a rule pass a test while failing on the
 * corpus it exists to measure.
 *
 * The tests are asymmetric on purpose. A missed pair only overstates the gap, which the next audit
 * catches. A **false** pair claims we already report something we do not, erasing a finding in a way
 * nothing downstream can see — so the refusal cases here matter more than the matches.
 */
final class JavaFindingMatcherTest extends TestCase
{
    private JavaFindingMatcher $matcher;

    protected function setUp(): void
    {
        $this->matcher = new JavaFindingMatcher();
    }

    /**
     * The case that proves a BELOW count is not a missing-check count.
     *
     * `R4.Observation-ex-pain`: the reference validator reports two errors, we report one, and ours is
     * its first one worded the Symfony way. The case is one finding short, not two — and reading it as
     * two is what inflated the original estimate for this work.
     */
    public function testACardinalityFindingPairsWithOurBlankValueOnTheSameElement(): void
    {
        $java = [
            'Observation.code: minimum required = 1, but only found 0 (from http://hl7.org/fhir/StructureDefinition/Observation|4.0.1)',
            "The property 'value' is invalid",
        ];

        $unmatched = $this->matcher->unmatched($java, [$this->notBlank('code')], ['Observation', 'Observation']);

        self::assertSame(["The property 'value' is invalid"], $unmatched);
    }

    /**
     * Java's expression is our own path once its annotations and root type are stripped.
     *
     * Expression copied verbatim from `outcomes/java/R4.bundle-ea-testcase-base.json`, which is the deepest
     * instance in the corpus: a List inside a Bundle contained in a MeasureReport inside a Bundle. Java
     * names the element by type in the message (`List.status`) and the instance in the expression; we carry
     * both in one path. Stripping `/*Type/id*​/` and the leading `Bundle.` makes them the same string.
     */
    public function testCardinalityPairingComparesTheInstanceAndNotJustTheElement(): void
    {
        $java = ['List.status: minimum required = 1, but only found 0'];
        $ours = [$this->notBlank('entry[1].resource.contained[0].entry[0].resource.status')];
        $expr = ['Bundle.entry[1].resource/*MeasureReport/measurereport-denom-EXM104*/'
            . '.contained[0]/*Bundle/4e9ea2cf-bdfc-460f-b7a0-49f70201e177*/'
            . '.entry[0].resource/*List/1a19a371-91b8-4a1d-9bb0-e8a997baa655*/'];

        self::assertSame([], $this->matcher->unmatched($java, $ours, $expr));
    }

    /**
     * The false pair this rule used to be able to make.
     *
     * Two Lists in one document that both lack `status` produce two identical messages, so matching on the
     * element name alone pairs them in arrival order — the aggregate stays right while the attribution is a
     * coin toss, and nothing downstream can see it. Here the reference finding is about the List in
     * `entry[0]` and ours is about the one in `entry[1]`, which is not a pair.
     */
    public function testCardinalityPairingRefusesADifferentInstanceOfTheSameType(): void
    {
        $java = ['List.status: minimum required = 1, but only found 0'];
        $ours = [$this->notBlank('entry[1].resource.status')];
        $expr = ['Bundle.entry[0].resource/*List/first*/'];

        self::assertSame($java, $this->matcher->unmatched($java, $ours, $expr));
    }

    /**
     * With no expression there is no instance, so there is no pair.
     *
     * All 108 cardinality findings in the vendored corpus carry one, so this refusal costs nothing today.
     * It exists so the rule cannot quietly revert to name-only matching if a future outcome omits the
     * field — the direction that overstates the gap is the safe one.
     */
    public function testCardinalityPairingRefusesWhenTheOutcomeDoesNotSayWhere(): void
    {
        $java = ['List.status: minimum required = 1, but only found 0'];
        $ours = [$this->notBlank('entry[1].resource.status')];

        self::assertSame($java, $this->matcher->unmatched($java, $ours, ['']));
    }

    /** A cardinality finding on a different element is a different finding. */
    public function testCardinalityPairingRefusesADifferentElement(): void
    {
        $java = ['Observation.code: minimum required = 1, but only found 0'];

        self::assertSame($java, $this->matcher->unmatched($java, [$this->notBlank('subject')], ['Observation']));
    }

    /** Both sides take the key from the same StructureDefinition, so agreement is identity. */
    public function testAnInvariantPairsOnItsKey(): void
    {
        $java = ["Constraint failed: bdl-5: 'must be a resource unless there's a request or response'"];
        $ours = [$this->invariant('bdl-5', 'entry[5]', "must be a resource unless there's a request or response")];

        self::assertSame([], $this->matcher->unmatched($java, $ours));
    }

    /** A different invariant on the same element is a different rule. */
    public function testAnInvariantRefusesADifferentKey(): void
    {
        $java = ["Constraint failed: bdl-5: 'must be a resource unless there's a request or response'"];
        $ours = [$this->invariant('bdl-7', 'entry[5]', 'FullUrl must be unique in a bundle')];

        self::assertSame($java, $this->matcher->unmatched($java, $ours));
    }

    /** Quoted values differ between renderings of one rule; the rule wording does not. */
    public function testIdenticalWordingPairsDespiteDifferentQuotedValues(): void
    {
        $java = ["The fullUrl must be an absolute URL (not 'Patient/1')"];
        $ours = [$this->plain('entry[0].fullUrl', "The fullUrl must be an absolute URL (not 'Patient/1')")];

        self::assertSame([], $this->matcher->unmatched($java, $ours));
    }

    /**
     * The rule the class exists to prevent.
     *
     * Two validators complaining about `Patient.birthDate` may be applying different rules to it, so a
     * shared element is never on its own enough to pair. This is the false pair that would be invisible
     * afterwards, because the erased finding simply would not appear in the missing list.
     */
    public function testASharedElementAloneDoesNotPair(): void
    {
        $java = ['Not a valid date format: \'not a date\''];
        $ours = [$this->plain('birthDate', 'This value should not be blank.')];

        self::assertSame($java, $this->matcher->unmatched($java, $ours));
    }

    /** One finding of ours cannot explain two of Java's. */
    public function testOneFindingOfOursIsConsumedByOneOfJavas(): void
    {
        $java = [
            'Observation.code: minimum required = 1, but only found 0',
            'Observation.code: minimum required = 1, but only found 0',
        ];

        $unmatched = $this->matcher->unmatched($java, [$this->notBlank('code')], ['Observation', 'Observation']);

        self::assertCount(1, $unmatched);
    }

    /**
     * A document is unreadable once, however many places the reference validator noticed.
     *
     * `R4.json-no-quotes-2` yields three parse diagnostics against our single rejection. Pairing those
     * one-to-one would score two of them as checks we lack while we already reject the whole file.
     */
    public function testOneRefusalToReadExplainsEveryParseDiagnostic(): void
    {
        $java = [
            "The JSON property 'item' has no quotes around the value of the property 'Sarah'",
            "The JSON property 'line' has no quotes around the name of the property",
            "The JSON property 'city' has no quotes around the value of the property 'Strahan'",
        ];

        self::assertSame([], $this->matcher->unmatched($java, [$this->unreadable()]));
    }

    /**
     * Refusing to read a file does not mean the reference validator refused it.
     *
     * `R4.japanese-utf8-ok` is the proof: we reject the encoding, Java tolerates it and goes on to report
     * 108 ordinary validation findings. Those are real gaps, and a read-failure rule that swallowed them
     * would hide 108 of them behind one encoding fix.
     */
    public function testARefusalToReadDoesNotExplainOrdinaryValidationFindings(): void
    {
        $java = [
            "Undefined element 'code' at Device",
            "Text should not be present ('x')",
        ];

        self::assertSame($java, $this->matcher->unmatched($java, [$this->unreadable()]));
    }

    /**
     * A validation-shaped diagnostic stays unmatched even on a document we refused.
     *
     * `R5.observation-with-trailing-dot` holds `"value": 925.`. Our reader rejects the document; Java
     * reads it and reports an invalid decimal. That wording is indistinguishable from the validation rule
     * of the same name, which we do not implement, so it is left unmatched — overstating the gap by one
     * rather than risking a rule that could swallow the japanese-utf8-ok findings above.
     */
    public function testAnInvalidDecimalIsNotTreatedAsAReadFailure(): void
    {
        $java = ["The value '925.' is not a valid decimal"];

        self::assertSame($java, $this->matcher->unmatched($java, [$this->unreadable()]));
    }

    /** Every claimed pairing names the rule that made it, so precision can be reviewed by eye. */
    public function testEachPairingReportsTheRuleThatMadeIt(): void
    {
        $pairs = $this->matcher->matchedPairs(
            ['Observation.code: minimum required = 1, but only found 0'],
            [$this->notBlank('code')],
            ['Observation'],
        );

        self::assertCount(1, $pairs);
        self::assertSame('cardinality', $pairs[0]['rule']);
        self::assertSame('code', $pairs[0]['ourPath']);
    }

    /**
     * A finding we already report, in wording nothing else can recognise.
     *
     * `AbstractResource::$id` carries the FHIR id pattern as a `Regex`, so we do report this — but the two
     * messages share no sentence, and `resource-invalid-id-1` read `ours 1, java 1` while still counting
     * one finding as missing. Text and expression verbatim from
     * `outcomes/java/R4.resource-invalid-id-1-base.json`.
     */
    public function testASharedDistinctivePhraseOnTheSameElementPairs(): void
    {
        $java = ["Invalid Resource id: Invalid Characters ('/foobar==')"];
        $ours = [$this->plain('id', 'Invalid Resource id: "/foobar==" must be 1-64 characters of A-Z, a-z, 0-9, "-" or ".".')];

        self::assertSame([], $this->matcher->unmatched($java, $ours, ['Location.id']));
    }

    /**
     * The expression names the element itself here, not its container.
     *
     * Opposite of the cardinality rule, and the reason this one compares the full path. Expression from
     * `R4.contained-resource-bad-id`, the deepest instance the phrase table pairs.
     */
    public function testASharedPhrasePairsOnANestedInstance(): void
    {
        $java = ['Invalid Resource id: Too long (65 chars)'];
        $ours = [$this->plain('contained[0].id', 'Invalid Resource id: "123" must be 1-64 characters of A-Z, a-z, 0-9, "-" or ".".')];
        $expr = ['Condition.contained[0]/*Practitioner/123*/.id'];

        self::assertSame([], $this->matcher->unmatched($java, $ours, $expr));
    }

    /** Two id complaints about different resources in one document are two findings, not one. */
    public function testASharedPhraseRefusesADifferentElement(): void
    {
        $java = ["Invalid Resource id: Invalid Characters ('bad')"];
        $ours = [$this->plain('contained[0].id', 'Invalid Resource id: "other" must be 1-64 characters.')];

        self::assertSame($java, $this->matcher->unmatched($java, $ours, ['Patient.id']));
    }

    /**
     * The phrase has to appear on both sides, or it is evidence of nothing.
     *
     * Without this the rule would degrade into pairing anything the reference validator says about an
     * element we happen to have a finding on — the path-only pairing this class refuses.
     */
    public function testASharedPhraseRefusesWhenOnlyOneSideUsesIt(): void
    {
        $java = ["Invalid Resource id: Invalid Characters ('bad')"];
        $ours = [$this->plain('id', 'This value should not be blank.')];

        self::assertSame($java, $this->matcher->unmatched($java, $ours, ['Patient.id']));
    }

    /**
     * The reference validator describes one of our invariants without naming it.
     *
     * `ele-1` against `Element must have some content`, with no `Constraint failed:` wrapper to pair on.
     * The expression is XPath because the finding comes from XML validation, and XPath carries no repeat
     * indexes — so `/f:Group/f:characteristic/f:code` has to match our `characteristic[0].code` with
     * indexes stripped. Verbatim from `outcomes/java/R5.group-choice-empty-base.json`.
     */
    public function testAnInvariantPairsWithTheReferenceParaphraseOfIt(): void
    {
        $java = ['Element must have some content'];
        $ours = [$this->invariant('ele-1', 'characteristic[0].code', 'All FHIR elements must have a @value or children')];

        self::assertSame([], $this->matcher->unmatched($java, $ours, ['/f:Group/f:characteristic/f:code']));
    }

    /** A paraphrase is tied to the one invariant it paraphrases, so another key is not a pair. */
    public function testAParaphraseRefusesADifferentInvariant(): void
    {
        $java = ['Element must have some content'];
        $ours = [$this->invariant('ele-2', 'characteristic[0].code', 'Must have either extensions or value')];

        self::assertSame($java, $this->matcher->unmatched($java, $ours, ['/f:Group/f:characteristic/f:code']));
    }

    /**
     * Index stripping is a concession to XPath only.
     *
     * A FHIRPath expression carries indexes, so it must match with them. Relaxing here as well would make
     * two empty entries in one list indistinguishable when the source can in fact tell them apart.
     */
    public function testAParaphraseStillRequiresExactPathsForFhirPathExpressions(): void
    {
        $java = ['Element must have some content'];
        $ours = [$this->invariant('ele-1', 'entry[1]', 'All FHIR elements must have a @value or children')];

        self::assertSame($java, $this->matcher->unmatched($java, $ours, ['List.entry[0]']));
    }

    /**
     * The paraphrase deliberately left out of the table.
     *
     * `empty-array` is the same defect, but the reference validator points one level deeper than we do —
     * `DocumentReference.category[0].coding` against our `category[0]` — so pairing it needs a prefix
     * comparison. One overstated finding is cheaper than a rule that pairs on containment.
     */
    public function testTheDeeperArrayParaphraseIsNotPaired(): void
    {
        $java = ['Array cannot be empty - the property should not be present if it has no values'];
        $ours = [$this->invariant('ele-1', 'category[0]', 'All FHIR elements must have a @value or children')];

        self::assertSame($java, $this->matcher->unmatched($java, $ours, ['DocumentReference.category[0].coding']));
    }

    private function notBlank(string $path): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: 'This value should not be blank.',
            constraintClass: 'Symfony\Component\Validator\Constraints\NotBlank',
            profileGroup: null,
            invariantKey: null,
        );
    }

    private function invariant(string $key, string $path, string $message): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRInvariant',
            profileGroup: null,
            invariantKey: $key,
        );
    }

    private function plain(string $path, string $message): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRCheck',
            profileGroup: null,
            invariantKey: null,
        );
    }

    /** Our finding for a document we would not read, tagged the way the harness tags it. */
    private function unreadable(): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: '',
            message: 'Unable to parse JSON: Parse error on line 7',
            constraintClass: '',
            profileGroup: null,
            invariantKey: null,
            code: JavaFindingMatcher::UNREADABLE_DOCUMENT_CODE,
        );
    }
}
