<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Validation\FHIRPathResourceContext;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Evaluates a FHIRPath invariant expression against the validated value.
 *
 * Enforces a #[FHIRPathInvariant] constraint by running its expression through the FHIRPath
 * engine; the invariant passes only when the result is the single boolean true. A failing
 * invariant raises a WARNING or ERROR violation depending on the constraint's severity, while
 * an expression the engine cannot evaluate is surfaced separately as an eval-error rather than
 * a conformance failure.
 */
final class FHIRPathInvariantValidator extends ConstraintValidator
{
    /**
     * @param bool $reportBestPractice Whether to evaluate constraints marked
     *                                 `elementdefinition-bestpractice`. Off by default, matching the
     *                                 HL7 Java reference validator: these express a recommendation,
     *                                 not a conformance rule, so reporting them buries real findings
     *                                 — `dom-6` ("a resource should have narrative") alone accounted
     *                                 for 475 of 767 warnings across the R4 conformance corpus. Turn
     *                                 on to audit narrative and other best-practice coverage.
     */
    public function __construct(
        private readonly FHIRPathService $pathService,
        private readonly FHIRValidationMessageRegistry $messageRegistry,
        private readonly bool $reportBestPractice = false,
    ) {
    }

    /**
     * Whether every contained resource `dom-3` flagged is in fact referenced from XHTML narrative.
     *
     * `dom-3` requires each contained resource to be referred to from elsewhere in the containing
     * resource. Its published FHIRPath expression looks for the `#id` fragment among `reference`,
     * `canonical`, `uri` and `url` elements only — it cannot see inside `Narrative.div`, because the
     * markup is a single `xhtml` primitive and its `img/@src` is not a FHIR element at all. A resource
     * whose only pointer to a contained Binary is `<img src="#pic1"/>` is valid FHIR (the spec names
     * exactly this pattern) and the HL7 Java validator reports nothing on it, because Java hand-codes
     * `dom-3` and does inspect the narrative. We were raising a false error.
     *
     * Rather than reimplement `dom-3` — the expression is generated model metadata and cannot be edited
     * here — this re-runs the resource's *own* expression against a shallow copy from which the
     * narrative-referenced contained resources have been removed. If the expression then passes, every
     * remaining contained resource is properly referenced and the removed ones are accounted for by the
     * narrative, so the invariant is satisfied.
     *
     * The construction is deliberately one-directional: it can only ever turn a reported failure into a
     * pass, never the reverse. If the re-run still fails, or throws, or nothing was removed, the caller
     * reports the original violation unchanged. That is what makes it safe to apply to every version —
     * note in particular that R4/R4B never reach here at all, because their `dom-3` expression uses
     * `as(canonical)` on a multi-item collection, which the engine rejects as an eval-error upstream.
     */
    private function narrativeAccountsForContained(mixed $value, FHIRPathInvariant $constraint): bool
    {
        if (!is_object($value)) {
            return false;
        }

        $contained = FHIRPathResourceContext::readSegment($value, 'contained');
        if (!is_array($contained) || $contained === []) {
            return false;
        }

        $narrativeTargets = self::narrativeFragmentTargets($value, $contained);
        if ($narrativeTargets === []) {
            return false;
        }

        $remaining = [];
        foreach ($contained as $item) {
            $id = self::readIdOf($item);
            if ($id !== null && in_array($id, $narrativeTargets, true)) {
                continue;
            }

            $remaining[] = $item;
        }

        if (count($remaining) === count($contained)) {
            return false;
        }

        try {
            $probe = clone $value;
            (new \ReflectionProperty($value, 'contained'))->setValue($probe, $remaining);

            $result = $this->pathService->evaluate($constraint->expression, $probe, new EvaluationContext($probe));
        } catch (FHIRPathException|\ReflectionException|\Error) {
            return false;
        }

        return $result->count() === 1 && $result->first() === true;
    }

    /**
     * Fragment ids (`#pic1` → `pic1`) referenced from `src`/`href` attributes in any narrative markup
     * belonging to this resource — its own `text.div` and that of each contained resource, since a
     * contained resource's narrative is still "elsewhere in the containing resource".
     *
     * @param list<mixed>|array<array-key, mixed> $contained
     *
     * @return list<string>
     */
    private static function narrativeFragmentTargets(object $resource, array $contained): array
    {
        $markup = [self::narrativeMarkupOf($resource)];
        foreach ($contained as $item) {
            $markup[] = self::narrativeMarkupOf($item);
        }

        $targets = [];
        foreach ($markup as $html) {
            if ($html === null) {
                continue;
            }

            if (preg_match_all('/(?:src|href)\s*=\s*(["\'])#([^"\'\s>]+)\1/i', $html, $matches) === false) {
                continue;
            }

            foreach ($matches[2] as $target) {
                $targets[] = $target;
            }
        }

        return array_values(array_unique($targets));
    }

    /**
     * `Narrative.div` markup as a string, for a resource held either as a model object (XML path) or as
     * a decoded array (the JSON path leaves `contained` entries as raw arrays).
     */
    private static function narrativeMarkupOf(mixed $node): ?string
    {
        if (is_array($node)) {
            $text = $node['text'] ?? null;
            $div  = is_array($text) ? ($text['div'] ?? null) : null;

            return is_string($div) ? $div : null;
        }

        if (!is_object($node)) {
            return null;
        }

        $text = FHIRPathResourceContext::readSegment($node, 'text');
        if (!is_object($text)) {
            return null;
        }

        $div = FHIRPathResourceContext::readSegment($text, 'div');
        if (is_string($div)) {
            return $div;
        }

        return is_object($div) && $div instanceof \Stringable ? (string) $div : null;
    }

    /** The `id` of a contained resource, whether it is a model object or a decoded array. */
    private static function readIdOf(mixed $node): ?string
    {
        if (is_array($node)) {
            $id = $node['id'] ?? null;

            return is_string($id) ? $id : null;
        }

        if (!is_object($node)) {
            return null;
        }

        $id = FHIRPathResourceContext::readSegment($node, 'id');

        return is_string($id) ? $id : null;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRPathInvariant) {
            throw new UnexpectedTypeException($constraint, FHIRPathInvariant::class);
        }

        if ($value === null) {
            return;
        }

        // A best-practice constraint is a recommendation, not a conformance rule. Skip before
        // evaluating rather than after: these fire on almost every resource, so the saved FHIRPath
        // evaluations are the bulk of them.
        if ($constraint->bestPractice && !$this->reportBestPractice) {
            return;
        }

        // ele-1, ext-1 and ref-1 run on nearly every node and nearly always pass; when the answer
        // certainly is a pass, skip the engine. See FHIRPathInvariantFastPaths.
        if (FHIRPathInvariantFastPaths::passes($constraint->expression, $value) === true) {
            return;
        }

        try {
            $result = $this->pathService->evaluate(
                $constraint->expression,
                $value,
                FHIRPathResourceContext::at($this->context->getRoot(), (string) $this->context->getPropertyPath(), $this->context),
            );
        } catch (FHIRPathException) {
            // The engine could not evaluate the expression (e.g. an unsupported function).
            // Per the FHIR conformance spec this is a tooling limitation, not instance
            // non-conformance, so surface it distinctly instead of as a failed constraint.
            // Only FHIRPath engine exceptions are downgraded; any other throwable (a genuine
            // bug) propagates rather than being masked as a passing/info result.
            $this->context->buildViolation(sprintf(
                'FHIRPath invariant `%s` could not be evaluated: %s',
                $constraint->key,
                $constraint->expression,
            ))
                ->setCode(FHIRViolationCode::EVAL_ERROR)
                ->addViolation();

            return;
        }

        // An empty collection is not a failure. FHIRPath is three-valued: empty means "unknown", and
        // it arises constantly from legitimate data rather than from non-conformance. `ref-1` is
        // `reference.startsWith('#').not() or (reference.substring(1) in %rootResource.contained.id)`,
        // so a Reference carrying only `display` yields empty on every term, and a bare `#`
        // self-reference yields `false or {}` — which is `{}`, not `false`. Both are valid FHIR that
        // the HL7 Java validator passes with zero issues.
        //
        // Only an explicit single `false` is non-conformance. Verified on the discriminating pair:
        // `containedToContainer` evaluates empty on both its References (Java: 0 issues) while
        // `hakan-se` still evaluates to a hard `false` on its unresolvable local reference (Java:
        // reports ref-1). Collapsing empty into failure could not tell those apart.
        if ($result->isEmpty()) {
            return;
        }

        $passed = $result->count() === 1 && $result->first() === true;

        if ($passed) {
            return;
        }

        // `dom-3` needs one thing its own published expression cannot express: a contained resource
        // may be referenced from the narrative. See narrativeAccountsForContained().
        if ($constraint->key === 'dom-3' && $this->narrativeAccountsForContained($value, $constraint)) {
            return;
        }

        $code     = $constraint->severity === 'warning' ? FHIRViolationCode::WARNING : FHIRViolationCode::ERROR;
        $override = $this->messageRegistry->getOverride('FHIRPathInvariant');

        $this->context->buildViolation($override ?? $constraint->human)
            ->setCode($code)
            ->addViolation();
    }
}
