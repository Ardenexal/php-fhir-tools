<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<FHIRPathInvariantValidator>
 */
final class FHIRPathInvariantValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): FHIRPathInvariantValidator
    {
        return new FHIRPathInvariantValidator(new FHIRPathService(), new FHIRValidationMessageRegistry());
    }

    public function testNullValuePassesWithNoViolation(): void
    {
        $this->validator->validate(null, $this->makeConstraint('1 = 1', 'error'));

        $this->assertNoViolation();
    }

    public function testTrueExpressionPassesWithNoViolation(): void
    {
        $this->validator->validate(new \stdClass(), $this->makeConstraint('1 = 1', 'error'));

        $this->assertNoViolation();
    }

    public function testFalseExpressionWithErrorSeverityEmitsErrorViolation(): void
    {
        $constraint = $this->makeConstraint('1 = 2', 'error', 'inv-1', 'Value must be present.');
        $this->validator->validate(new \stdClass(), $constraint);

        $this->buildViolation('Value must be present.')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testFalseExpressionWithWarningSeverityEmitsWarningViolation(): void
    {
        $constraint = $this->makeConstraint('1 = 2', 'warning', 'inv-2', 'Consider providing a value.');
        $this->validator->validate(new \stdClass(), $constraint);

        $this->buildViolation('Consider providing a value.')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    public function testEngineExceptionEmitsEvalErrorNotFailure(): void
    {
        $stub = $this->createStub(FHIRPathService::class);
        $stub->method('evaluate')->willThrowException(new EvaluationException('unsupported function'));

        $validator = new FHIRPathInvariantValidator($stub, new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate(new \stdClass(), $this->makeConstraint('bad-expr', 'error', 'inv-3', 'Invariant failed.'));

        // An engine limitation must not be asserted as instance non-conformance:
        // it is reported with the distinct eval-error code, not the constraint's human message.
        $this->buildViolation('FHIRPath invariant `inv-3` could not be evaluated: bad-expr')
            ->setCode(FHIRViolationCode::EVAL_ERROR)
            ->assertRaised();
    }

    public function testNonEngineThrowablePropagatesInsteadOfBeingMasked(): void
    {
        $stub = $this->createStub(FHIRPathService::class);
        $stub->method('evaluate')->willThrowException(new \TypeError('genuine bug'));

        $validator = new FHIRPathInvariantValidator($stub, new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        // A non-FHIRPath throwable is a real bug, not a tooling limitation: it must surface
        // loudly rather than be downgraded to an info eval-error that hides the failure.
        $this->expectException(\TypeError::class);

        $validator->validate(new \stdClass(), $this->makeConstraint('bad-expr', 'error', 'inv-4', 'Invariant failed.'));
    }

    public function testRegistryOverrideIsUsedInViolationMessage(): void
    {
        $registry = new FHIRValidationMessageRegistry();
        $registry->setOverride('FHIRPathInvariant', 'Custom invariant message.');

        $validator = new FHIRPathInvariantValidator(new FHIRPathService(), $registry);
        $validator->initialize($this->context);

        $validator->validate(new \stdClass(), $this->makeConstraint('1 = 2', 'error'));

        $this->buildViolation('Custom invariant message.')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    private function makeConstraint(string $expression, string $severity, string $key = 'inv-1', string $human = 'Invariant failed.'): FHIRPathInvariant
    {
        return new FHIRPathInvariant(
            key: $key,
            severity: $severity,
            expression: $expression,
            human: $human,
        );
    }
}
