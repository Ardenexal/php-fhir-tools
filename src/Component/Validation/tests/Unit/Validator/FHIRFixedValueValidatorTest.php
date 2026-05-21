<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<FHIRFixedValueValidator>
 */
final class FHIRFixedValueValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): FHIRFixedValueValidator
    {
        return new FHIRFixedValueValidator(new FHIRValidationMessageRegistry());
    }

    public function testNullValuePassesWithNoViolation(): void
    {
        $this->validator->validate(null, new FHIRFixedValue('expected'));

        $this->assertNoViolation();
    }

    public function testExactMatchPassesWithNoViolation(): void
    {
        $this->validator->validate('expected', new FHIRFixedValue('expected'));

        $this->assertNoViolation();
    }

    public function testIntegerExactMatchPassesWithNoViolation(): void
    {
        $this->validator->validate(42, new FHIRFixedValue(42));

        $this->assertNoViolation();
    }

    public function testMismatchedValueEmitsErrorViolation(): void
    {
        $this->validator->validate('actual', new FHIRFixedValue('expected'));

        $this->buildViolation(FHIRFixedValueValidator::DEFAULT_MESSAGE)
            ->setParameters(['{{ value }}' => 'actual', '{{ fixed }}' => 'expected'])
            ->setInvalidValue('actual')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testTypeMismatchEmitsErrorViolation(): void
    {
        $this->validator->validate('42', new FHIRFixedValue(42));

        $this->buildViolation(FHIRFixedValueValidator::DEFAULT_MESSAGE)
            ->setParameters(['{{ value }}' => '42', '{{ fixed }}' => '42'])
            ->setInvalidValue('42')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testRegistryOverrideIsUsedInViolationMessage(): void
    {
        $registry = new FHIRValidationMessageRegistry();
        $registry->setOverride('FHIRFixedValue', 'Custom fixed value message.');

        $validator = new FHIRFixedValueValidator($registry);
        $validator->initialize($this->context);

        $validator->validate('wrong', new FHIRFixedValue('right'));

        $this->buildViolation('Custom fixed value message.')
            ->setParameters(['{{ value }}' => 'wrong', '{{ fixed }}' => 'right'])
            ->setInvalidValue('wrong')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }
}
