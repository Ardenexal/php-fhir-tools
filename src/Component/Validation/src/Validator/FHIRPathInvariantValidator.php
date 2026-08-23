<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
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
     * Bind `%resource` / `%rootResource` / `%context` to the resource validation actually started at.
     *
     * The FHIRPath engine resolves all three from `EvaluationContext::getRootResource()` and returns
     * an empty collection when it is unset. Passing no context therefore leaves them unbound, and an
     * invariant like `Reference.ref-1` —
     * `reference.startsWith('#').not() or (reference.substring(1) in %rootResource.contained.id)` —
     * evaluates its right-hand side against nothing and reports a violation for every legitimate
     * local reference.
     *
     * This was latent rather than harmless: 32 R4 invariant declarations use one of these variables,
     * and they were simply never reached, because nested elements were not traversed at all. Emitting
     * `#[Assert\Valid]` from the model generator made them reachable and turned a dead check into a
     * false-positive one — `containedToContainer`, which the Java reference validator passes with zero
     * issues, reported two `ref-1` errors.
     *
     * The binding is the **nearest enclosing resource**, not the validation root. FHIR defines
     * `%resource` as "the resource that contains the original node", and resources nest: a `Coverage`
     * inside `Parameters.parameter.resource` carries its own `contained`, and a `#payer` reference
     * within it must resolve against *that* Coverage. Binding the validation root instead made
     * `ref-1` fail on every such reference, because `Parameters` has no `contained` of its own.
     *
     * Walking the property path is what makes this possible: Symfony hands us the root object plus a
     * path like `parameter[1].resource.payor[0]`, so the deepest `#[FhirResource]` along that path is
     * the enclosing resource. Falling back to the root keeps the common top-level case unchanged.
     */
    private function rootResourceContext(): EvaluationContext
    {
        $root = $this->context->getRoot();
        if (!is_object($root)) {
            return new EvaluationContext(null);
        }

        [$enclosing, $container] = $this->enclosingResourceAndContainer($root, (string) $this->context->getPropertyPath());

        return new EvaluationContext(rootResource: $enclosing, containerResource: $container);
    }

    /**
     * Walk `$path` from `$root`, returning `[nearest enclosing resource, its container]`, where the container is non-null only when
     * the enclosing resource was reached through a `contained` property.
     *
     * `%resource` is the nearest enclosing resource (M01's finding, unchanged). `%rootResource` is one
     * level out, but *only* for contained resources: FHIR defines it as "the container resource for the
     * resource that contains the original node", and a local `#id` reference inside a contained resource
     * addresses a sibling of that contained resource — so it must resolve against the container's
     * `contained`, not the contained resource's own (usually absent) one. `document-manifest` proves it:
     * its three local references all sit inside `contained[4]`, and all three reported `ref-1` against
     * the oracle's zero until `%rootResource` reached the DocumentManifest.
     *
     * The container is the **immediate** container, never the outermost root. For a node inside
     * `Bundle.entry[0].resource.contained[0]`, `%rootResource` is the entry's resource; binding the
     * Bundle instead would resolve `#id` against the wrong `contained` set and still pass
     * `document-manifest`.
     *
     * A resource reached any other way — `Parameters.parameter.resource`, `Bundle.entry.resource` — is
     * its own root, so the container stays null and `%rootResource` falls back to `%resource`. That is
     * what keeps M02's F3 closure (`containedToContainer`, whose `#payer` lives in the nested Coverage's
     * own `contained`) passing.
     *
     * Navigation is deliberately forgiving — an unreadable or absent segment simply stops the walk and
     * returns the best resource found so far, which is always at least the root. Binding a slightly
     * shallower resource degrades an invariant to its previous behaviour; throwing here would abort
     * validation of an otherwise valid document.
     *
     * @return array{0: object, 1: object|null}
     */
    private function enclosingResourceAndContainer(object $root, string $path): array
    {
        $nearest   = $root;
        $container = null;
        $current   = $root;
        $lastProp  = null;

        foreach (self::pathSegments($path) as $segment) {
            if (!is_object($current) && !is_array($current)) {
                break;
            }

            $next = self::readSegment($current, $segment);
            if ($next === null) {
                break;
            }

            $current = $next;
            if (is_object($current) && self::isResource($current)) {
                // `contained[4]` arrives on the numeric segment, so the property navigated through is
                // the last non-numeric one; a hypothetical single-valued `contained` arrives on the
                // property segment itself. Both read the same way here.
                $arrivedVia = is_numeric($segment) ? $lastProp : $segment;
                $container  = $arrivedVia === 'contained' ? $nearest : null;
                $nearest    = $current;
            }

            if (!is_numeric($segment)) {
                $lastProp = $segment;
            }
        }

        return [$nearest, $container];
    }

    /**
     * Split `parameter[1].resource.payor[0]` into `['parameter', '1', 'resource', 'payor', '0']`.
     *
     * @return list<string>
     */
    private static function pathSegments(string $path): array
    {
        if ($path === '') {
            return [];
        }

        $normalized = str_replace(['[', ']'], ['.', ''], $path);

        return array_values(array_filter(explode('.', $normalized), static fn (string $s): bool => $s !== ''));
    }

    private static function readSegment(mixed $current, string $segment): mixed
    {
        if (is_array($current)) {
            return $current[$segment] ?? null;
        }

        if (!is_object($current) || !property_exists($current, $segment)) {
            return null;
        }

        // Typed properties on generated models may be declared but never assigned; reading one of
        // those is an Error, not a null. See the property_exists footgun in model-object-initialization.
        try {
            if (!(new \ReflectionProperty($current, $segment))->isInitialized($current)) {
                return null;
            }
        } catch (\ReflectionException) {
            // Dynamic property — readable.
        }

        return $current->{$segment};
    }

    /**
     * Whether this object is a FHIR resource, checking ancestors too.
     *
     * `#[FhirResource]` is not inherited by reflection, and the marker sits on both the concrete
     * class and `AbstractResource`, so the walk covers generated subclasses either way. The attribute
     * is the version-agnostic test — `AbstractResource` itself is declared once per FHIR version.
     */
    private static function isResource(object $value): bool
    {
        for ($class = new \ReflectionClass($value); $class !== false; $class = $class->getParentClass()) {
            if ($class->getAttributes(FhirResource::class) !== []) {
                return true;
            }
        }

        return false;
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

        $contained = self::readSegment($value, 'contained');
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

        $text = self::readSegment($node, 'text');
        if (!is_object($text)) {
            return null;
        }

        $div = self::readSegment($text, 'div');
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

        $id = self::readSegment($node, 'id');

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

        try {
            $result = $this->pathService->evaluate(
                $constraint->expression,
                $value,
                $this->rootResourceContext(),
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
