<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMaxLength;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRMaxLengthValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<FHIRMaxLengthValidator>
 */
#[CoversClass(FHIRMaxLengthValidator::class)]
final class FHIRMaxLengthValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): FHIRMaxLengthValidator
    {
        return new FHIRMaxLengthValidator(new FHIRValidationMessageRegistry());
    }

    /**
     * A limit counts characters, not bytes: 'é' is two bytes in UTF-8.
     */
    public function testValueAtTheLimitPasses(): void
    {
        $this->validator->validate(new StringPrimitive(value: 'abc'), new FHIRMaxLength(3));
        $this->validator->validate('ééé', new FHIRMaxLength(3));
        $this->validator->validate(null, new FHIRMaxLength(3));

        $this->assertNoViolation();
    }

    public function testLongerWrapperIsReported(): void
    {
        $value = new StringPrimitive(value: 'abcd');

        $this->validator->validate($value, new FHIRMaxLength(3));

        $this->buildViolation(FHIRMaxLengthValidator::DEFAULT_MESSAGE)
            ->setParameter('{{ max }}', '3')
            ->setInvalidValue($value)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testEachItemOfARepeatingPropertyIsCheckedAtItsIndex(): void
    {
        $this->validator->validate(['abc', 'abcd'], new FHIRMaxLength(3));

        $this->buildViolation(FHIRMaxLengthValidator::DEFAULT_MESSAGE)
            ->setParameter('{{ max }}', '3')
            ->setInvalidValue('abcd')
            ->setCode(FHIRViolationCode::ERROR)
            ->atPath('property.path[1]')
            ->assertRaised();
    }

    public function testValueWithNoStringFormIsLeftAlone(): void
    {
        $this->validator->validate(12345, new FHIRMaxLength(3));
        $this->validator->validate(new \stdClass(), new FHIRMaxLength(3));

        $this->assertNoViolation();
    }
}
