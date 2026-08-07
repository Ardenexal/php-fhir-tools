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

        return new EvaluationContext($this->nearestEnclosingResource($root, (string) $this->context->getPropertyPath()));
    }

    /**
     * Walk `$path` from `$root`, returning the deepest object that is itself a FHIR resource.
     *
     * Navigation is deliberately forgiving — an unreadable or absent segment simply stops the walk and
     * returns the best resource found so far, which is always at least the root. Binding a slightly
     * shallower resource degrades an invariant to its previous behaviour; throwing here would abort
     * validation of an otherwise valid document.
     */
    private function nearestEnclosingResource(object $root, string $path): object
    {
        $nearest = $root;
        $current = $root;

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
                $nearest = $current;
            }
        }

        return $nearest;
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

        $code     = $constraint->severity === 'warning' ? FHIRViolationCode::WARNING : FHIRViolationCode::ERROR;
        $override = $this->messageRegistry->getOverride('FHIRPathInvariant');

        $this->context->buildViolation($override ?? $constraint->human)
            ->setCode($code)
            ->addViolation();
    }
}
