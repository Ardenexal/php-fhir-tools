<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * Verifies strict-mode and maxValueSet behaviour added in M06b:
 * - strict=true escalates extensible/preferred WARNING to ERROR
 * - maxValueSetUrl set + code invalid → ERROR violation (regardless of strict)
 * - maxValueSetUrl set + code valid → no maxValueSet violation
 * - code outside BOTH primary VS and maxValueSet → two violations (WARNING + ERROR)
 * - null client skips both checks (graceful degradation)
 *
 * @extends ConstraintValidatorTestCase<FHIRValueSetBindingValidator>
 */
final class FHIRValueSetBindingValidatorStrictMaxValueSetTest extends ConstraintValidatorTestCase
{
    private const string VS_URL     = 'http://hl7.org/fhir/ValueSet/languages';

    private const string MAX_VS_URL = 'http://hl7.org/fhir/ValueSet/all-languages';

    /** @var MockObject&FHIRTerminologyClientInterface */
    private MockObject $mockClient;

    protected function setUp(): void
    {
        $this->mockClient = $this->createMock(FHIRTerminologyClientInterface::class);
        parent::setUp();
    }

    protected function createValidator(): FHIRValueSetBindingValidator
    {
        return new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
    }

    // -------------------------------------------------------------------------
    // strict=true escalates WARNING to ERROR
    // -------------------------------------------------------------------------

    public function testStrictModeExtensibleProducesErrorViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(false);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('bad-code', new FHIRValueSetBinding(self::VS_URL, 'extensible', strict: true));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'bad-code', '{{ url }}' => self::VS_URL])
            ->setInvalidValue('bad-code')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testStrictModePreferredProducesErrorViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(false);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('bad-code', new FHIRValueSetBinding(self::VS_URL, 'preferred', strict: true));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'bad-code', '{{ url }}' => self::VS_URL])
            ->setInvalidValue('bad-code')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testStrictModeValidCodeProducesNoViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(true);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $validator->validate('en', new FHIRValueSetBinding(self::VS_URL, 'extensible', strict: true));

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // maxValueSetUrl — code outside maxValueSet → ERROR
    // -------------------------------------------------------------------------

    public function testMaxValueSetCodeOutsideMaxSetProducesErrorViolation(): void
    {
        // binding VS: valid, maxValueSet: invalid
        $this->mockClient
            ->method('validateCode')
            ->willReturnCallback(fn (string $url) => $url === self::VS_URL);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $constraint = new FHIRValueSetBinding(self::VS_URL, 'extensible', maxValueSetUrl: self::MAX_VS_URL);
        $validator->validate('some-extension-code', $constraint);

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'some-extension-code', '{{ url }}' => self::MAX_VS_URL])
            ->setInvalidValue('some-extension-code')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testMaxValueSetCodeInsideMaxSetProducesNoMaxViolation(): void
    {
        $this->mockClient->method('validateCode')->willReturn(true);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $constraint = new FHIRValueSetBinding(self::VS_URL, 'extensible', maxValueSetUrl: self::MAX_VS_URL);
        $validator->validate('en', $constraint);

        $this->assertNoViolation();
    }

    // -------------------------------------------------------------------------
    // Code outside both primary VS and maxValueSet → two violations
    // -------------------------------------------------------------------------

    public function testCodeOutsideBothSetsProducesWarningAndError(): void
    {
        // Both primary VS and maxValueSet return false → two violations
        $this->mockClient->method('validateCode')->willReturn(false);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $constraint = new FHIRValueSetBinding(self::VS_URL, 'extensible', maxValueSetUrl: self::MAX_VS_URL);
        $validator->validate('completely-invalid', $constraint);

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'completely-invalid', '{{ url }}' => self::VS_URL])
            ->setInvalidValue('completely-invalid')
            ->setCode(FHIRViolationCode::WARNING)
            ->buildNextViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'completely-invalid', '{{ url }}' => self::MAX_VS_URL])
            ->setInvalidValue('completely-invalid')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testStrictModeCodeOutsideBothSetsProducesTwoErrors(): void
    {
        // strict=true escalates primary VS violation from WARNING to ERROR
        $this->mockClient->method('validateCode')->willReturn(false);

        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->mockClient);
        $validator->initialize($this->context);

        $constraint = new FHIRValueSetBinding(self::VS_URL, 'extensible', strict: true, maxValueSetUrl: self::MAX_VS_URL);
        $validator->validate('completely-invalid', $constraint);

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'completely-invalid', '{{ url }}' => self::VS_URL])
            ->setInvalidValue('completely-invalid')
            ->setCode(FHIRViolationCode::ERROR)
            ->buildNextViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'completely-invalid', '{{ url }}' => self::MAX_VS_URL])
            ->setInvalidValue('completely-invalid')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // -------------------------------------------------------------------------
    // Null client skips all non-required checks, surfacing the gap as INFO (#71)
    // -------------------------------------------------------------------------

    public function testNullClientSkipsStrictCheckWithUncheckedBindingInfo(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $validator->validate('anything', new FHIRValueSetBinding(self::VS_URL, 'extensible', strict: true));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::VS_URL])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    public function testNullClientSkipsMaxValueSetCheckWithUncheckedBindingInfo(): void
    {
        $validator = new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry());
        $validator->initialize($this->context);

        $constraint = new FHIRValueSetBinding(self::VS_URL, 'extensible', maxValueSetUrl: self::MAX_VS_URL);
        $validator->validate('anything', $constraint);

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::VS_URL])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }
}
