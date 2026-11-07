<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * Named after a value set URL so the validator's name-based lookup finds it, but generated from no
 * value set: it carries no #[FHIRValueSetSource].
 */
enum MedicationStatementStatus: string
{
    case active = 'active';
}

/**
 * A binding that comes from a profile trusts only an enum generated from that exact value set, and
 * reports a required binding nothing can check as unchecked rather than as a missing enum.
 *
 * @extends ConstraintValidatorTestCase<FHIRValueSetBindingValidator>
 */
final class FHIRValueSetBindingValidatorFromProfileTest extends ConstraintValidatorTestCase
{
    use BindsValidatorContext;

    private const string URL = 'http://hl7.org/fhir/ValueSet/medication-statement-status';

    private bool $nullClient = false;

    protected function createValidator(): FHIRValueSetBindingValidator
    {
        return new FHIRValueSetBindingValidator(
            new FHIRValidationMessageRegistry(),
            [__NAMESPACE__],
            $this->nullClient ? new NullFHIRTerminologyClient() : null,
        );
    }

    /**
     * `unknown` is a valid medication-statement-status code that the enum the name points at lacks.
     * The profile's binding must not borrow that enum and call the code invalid.
     */
    public function testAnEnumThatOnlySharesTheValueSetsNameIsNotUsed(): void
    {
        $this->validator->validate('unknown', new FHIRValueSetBinding(self::URL, 'required', fromProfile: true));

        $this->assertUnchecked();
    }

    public function testTheSameBindingOutsideAProfileStillUsesTheEnum(): void
    {
        $this->validator->validate('unknown', new FHIRValueSetBinding(self::URL, 'required'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => 'unknown', '{{ url }}' => self::URL])
            ->setInvalidValue('unknown')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * A NullFHIRTerminologyClient checks nothing, so it must not pass every code as valid.
     */
    public function testARequiredBindingWithANullTerminologyClientIsUnchecked(): void
    {
        $this->nullClient = true;
        $this->validator  = $this->createValidator();
        $this->bindContext($this->validator);

        $this->validator->validate('unknown', new FHIRValueSetBinding('http://example.org/ValueSet/no-enum', 'required', fromProfile: true));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => 'http://example.org/ValueSet/no-enum'])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    private function assertUnchecked(): void
    {
        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::URL])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }
}
