<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

enum TestStatusEnum: string
{
    case active   = 'active';
    case inactive = 'inactive';
}

/**
 * @extends ConstraintValidatorTestCase<FHIRValueSetBindingValidator>
 */
final class FHIRValueSetBindingValidatorTest extends ConstraintValidatorTestCase
{
    private const string TEST_VALUE_SET_URL = 'http://test.example/ValueSet/test-status-enum';

    protected function createValidator(): FHIRValueSetBindingValidator
    {
        return new FHIRValueSetBindingValidator(
            new FHIRValidationMessageRegistry(),
            [__NAMESPACE__],
        );
    }

    public function testNullValuePassesWithNoViolation(): void
    {
        $this->validator->validate(null, new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->assertNoViolation();
    }

    public function testValidEnumCaseStringPassesWithNoViolation(): void
    {
        $this->validator->validate('active', new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->assertNoViolation();
    }

    public function testValidEnumInstancePassesWithNoViolation(): void
    {
        $this->validator->validate(TestStatusEnum::active, new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->assertNoViolation();
    }

    public function testInvalidStringEmitsErrorViolation(): void
    {
        $this->validator->validate('not-a-valid-case', new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'not-a-valid-case', '{{ url }}' => self::TEST_VALUE_SET_URL])
            ->setInvalidValue('not-a-valid-case')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testMissingEnumClassWithRequiredStrengthEmitsWarning(): void
    {
        $url        = 'http://test.example/ValueSet/no-such-enum';
        $constraint = new FHIRValueSetBinding($url, 'required');
        $this->validator->validate('anything', $constraint);

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_MISSING_ENUM_MESSAGE)
            ->setParameters(['{{ url }}' => $url])
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    public function testMissingEnumClassWithNonRequiredStrengthEmitsUncheckedBindingInfo(): void
    {
        $url        = 'http://test.example/ValueSet/no-such-enum';
        $constraint = new FHIRValueSetBinding($url, 'preferred');
        $this->validator->validate('anything', $constraint);

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => $url])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    public function testRegistryOverrideIsUsedInViolationMessage(): void
    {
        $registry = new FHIRValidationMessageRegistry();
        $registry->setOverride('FHIRValueSetBinding', 'Custom binding message.');

        $validator = new FHIRValueSetBindingValidator($registry, [__NAMESPACE__]);
        $validator->initialize($this->context);

        $validator->validate('not-valid', new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->buildViolation('Custom binding message.')
            ->setParameters(['{{ value }}' => 'not-valid', '{{ url }}' => self::TEST_VALUE_SET_URL])
            ->setInvalidValue('not-valid')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }
}
