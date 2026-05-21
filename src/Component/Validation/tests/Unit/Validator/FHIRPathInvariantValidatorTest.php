<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

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

    public function testEvaluationExceptionTreatedAsFailure(): void
    {
        $stub = $this->createStub(FHIRPathService::class);
        $stub->method('evaluate')->willThrowException(new \RuntimeException('parse error'));

        $validator = new FHIRPathInvariantValidator($stub, new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate(new \stdClass(), $this->makeConstraint('bad-expr', 'error', 'inv-3', 'Invariant failed.'));

        $this->buildViolation('Invariant failed.')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
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
