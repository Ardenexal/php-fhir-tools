<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Validator;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
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
    public function __construct(
        private readonly FHIRPathService $pathService,
        private readonly FHIRValidationMessageRegistry $messageRegistry,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FHIRPathInvariant) {
            throw new UnexpectedTypeException($constraint, FHIRPathInvariant::class);
        }

        if ($value === null) {
            return;
        }

        try {
            $result = $this->pathService->evaluate($constraint->expression, $value);
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
