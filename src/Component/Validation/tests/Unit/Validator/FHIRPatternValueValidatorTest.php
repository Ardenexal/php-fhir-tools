<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPatternValueValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<FHIRPatternValueValidator>
 */
final class FHIRPatternValueValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): FHIRPatternValueValidator
    {
        return new FHIRPatternValueValidator(new FHIRValidationMessageRegistry());
    }

    public function testNullValuePassesWithNoViolation(): void
    {
        $this->validator->validate(null, new FHIRPatternValue(['system' => 'http://example.com']));

        $this->assertNoViolation();
    }

    public function testFullSupersetPassesWithNoViolation(): void
    {
        $value = ['system' => 'http://example.com', 'code' => 'abc', 'display' => 'ABC'];
        $this->validator->validate($value, new FHIRPatternValue(['system' => 'http://example.com', 'code' => 'abc']));

        $this->assertNoViolation();
    }

    public function testExactMatchPassesWithNoViolation(): void
    {
        $value = ['system' => 'http://example.com', 'code' => 'abc'];
        $this->validator->validate($value, new FHIRPatternValue(['system' => 'http://example.com', 'code' => 'abc']));

        $this->assertNoViolation();
    }

    public function testMissingRequiredKeyEmitsErrorViolation(): void
    {
        $value = ['code' => 'abc'];
        $this->validator->validate($value, new FHIRPatternValue(['system' => 'http://example.com', 'code' => 'abc']));

        $this->buildViolation(FHIRPatternValueValidator::DEFAULT_MESSAGE)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testWrongValueForKeyEmitsErrorViolation(): void
    {
        $value = ['system' => 'http://other.com', 'code' => 'abc'];
        $this->validator->validate($value, new FHIRPatternValue(['system' => 'http://example.com', 'code' => 'abc']));

        $this->buildViolation(FHIRPatternValueValidator::DEFAULT_MESSAGE)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testNestedPatternMatchPassesWithNoViolation(): void
    {
        $value = ['coding' => ['system' => 'http://example.com', 'code' => 'X'], 'text' => 'extra'];
        $this->validator->validate($value, new FHIRPatternValue(['coding' => ['system' => 'http://example.com']]));

        $this->assertNoViolation();
    }

    public function testNestedPatternMismatchEmitsErrorViolation(): void
    {
        $value = ['coding' => ['system' => 'http://wrong.com']];
        $this->validator->validate($value, new FHIRPatternValue(['coding' => ['system' => 'http://example.com']]));

        $this->buildViolation(FHIRPatternValueValidator::DEFAULT_MESSAGE)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testRegistryOverrideIsUsedInViolationMessage(): void
    {
        $registry = new FHIRValidationMessageRegistry();
        $registry->setOverride('FHIRPatternValue', 'Custom pattern message.');

        $validator = new FHIRPatternValueValidator($registry);
        $validator->initialize($this->context);

        $validator->validate(['wrong' => 'x'], new FHIRPatternValue(['required_key' => 'y']));

        $this->buildViolation('Custom pattern message.')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }
}
