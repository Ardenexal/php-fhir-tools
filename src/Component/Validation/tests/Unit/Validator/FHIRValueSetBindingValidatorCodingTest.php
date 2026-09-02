<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * Declared here rather than reused from `FHIRValueSetBindingValidatorTest`, which owns a similar
 * fixture. That enum exists only because its own file was loaded, so a filtered run of this class
 * alone left it undeclared and every binding fell through to the "no enum class generated" warning.
 * The validator resolves an enum by turning the value set URL into a class name, so this name and
 * the URL below must stay in step.
 */
enum CodingBindingStatus: string
{
    case active   = 'active';
    case inactive = 'inactive';
}

/**
 * A binding on a `Coding` or `CodeableConcept` is checked, not skipped.
 *
 * Before this behaviour existed the object was dropped on both paths: `isTestableCode()` rejected it
 * outright for a required binding, and a non-required binding handed the whole object to the
 * terminology client, whose implementations return their default for a non-scalar. Every
 * `CodeableConcept` binding in the generated model went unchecked, `Condition.clinicalStatus` among
 * them, so a code from the wrong list validated clean.
 *
 * The enum is resolved by name from the value set URL, which is why `CodingBindingStatus` above and
 * `TEST_VALUE_SET_URL` below have to stay in step.
 *
 * @extends ConstraintValidatorTestCase<FHIRValueSetBindingValidator>
 */
final class FHIRValueSetBindingValidatorCodingTest extends ConstraintValidatorTestCase
{
    private const string TEST_VALUE_SET_URL = 'http://test.example/ValueSet/coding-binding-status';

    protected function createValidator(): FHIRValueSetBindingValidator
    {
        return new FHIRValueSetBindingValidator(
            new FHIRValidationMessageRegistry(),
            [__NAMESPACE__],
        );
    }

    public function testCodingCarryingABoundCodeIsSilent(): void
    {
        $this->validator->validate($this->coding('active'), new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->assertNoViolation();
    }

    public function testCodingCarryingAnUnboundCodeIsReported(): void
    {
        $this->validator->validate($this->coding('not-a-valid-case'), new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->expectInvalidValue('not-a-valid-case');
    }

    public function testCodeableConceptCarryingABoundCodeIsSilent(): void
    {
        $value = new CodeableConcept(coding: [$this->coding('inactive')]);

        $this->validator->validate($value, new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->assertNoViolation();
    }

    public function testCodeableConceptCarryingAnUnboundCodeIsReported(): void
    {
        $value = new CodeableConcept(coding: [$this->coding('not-a-valid-case')]);

        $this->validator->validate($value, new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->expectInvalidValue('not-a-valid-case');
    }

    /**
     * A concept may carry several codings and only one need be the bound code, so each is checked on
     * its own. This is the `Condition.clinicalStatus` shape: a translation alongside the real code.
     */
    public function testEveryCodingInAConceptIsCheckedIndependently(): void
    {
        $value = new CodeableConcept(coding: [$this->coding('active'), $this->coding('wrong')]);

        $this->validator->validate($value, new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->expectInvalidValue('wrong');
    }

    /**
     * FHIR allows a concept that carries only human text. Reporting one would be a new class of
     * finding rather than the membership check this behaviour adds, so it stays silent.
     */
    public function testConceptWithOnlyTextIsSilent(): void
    {
        $this->validator->validate(new CodeableConcept(text: 'free text'), new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->assertNoViolation();
    }

    /** An empty concept carries no code, so there is no membership question to answer. */
    public function testConceptWithNoCodingIsSilent(): void
    {
        $this->validator->validate(new CodeableConcept(), new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->assertNoViolation();
    }

    /** A repeating coded element hands over a PHP array; every concept inside it is checked. */
    public function testRepeatingConceptPropertyIsChecked(): void
    {
        $value = [
            new CodeableConcept(coding: [$this->coding('active')]),
            new CodeableConcept(coding: [$this->coding('bad-code')]),
        ];

        $this->validator->validate($value, new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->expectInvalidValue('bad-code');
    }

    /**
     * The system is deliberately not read, so a right code under a wrong system passes.
     *
     * That is a miss rather than a false positive. This rule switches on across every coded element at
     * once, so it is written to under-report rather than to invent findings on data it has not seen.
     * Pinned so the choice is visible if anyone later tightens it.
     */
    public function testCodeIsCheckedWithoutRegardToItsSystem(): void
    {
        $value = new CodeableConcept(coding: [$this->coding('active', 'http://example.org/some-other-system')]);

        $this->validator->validate($value, new FHIRValueSetBinding(self::TEST_VALUE_SET_URL));

        $this->assertNoViolation();
    }

    /**
     * A caller who configures no terminology client must see exactly what they saw before. A
     * non-required binding reports the unchecked-binding INFO and never a membership failure, whatever
     * the concept holds.
     */
    public function testConceptUnderANonRequiredBindingWithoutAClientStaysUnchecked(): void
    {
        $validator = new FHIRValueSetBindingValidator(
            new FHIRValidationMessageRegistry(),
            [__NAMESPACE__],
            new NullFHIRTerminologyClient(),
        );
        $validator->initialize($this->context);

        $value = new CodeableConcept(coding: [$this->coding('not-a-valid-case')]);

        $validator->validate($value, new FHIRValueSetBinding(self::TEST_VALUE_SET_URL, strength: 'preferred'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNCHECKED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::TEST_VALUE_SET_URL])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    private function coding(string $code, ?string $system = null): Coding
    {
        return new Coding(
            system: $system === null ? null : new UriPrimitive(value: $system),
            code: new CodePrimitive(value: $code),
        );
    }

    private function expectInvalidValue(string $code): void
    {
        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_INVALID_VALUE_MESSAGE)
            ->setParameters(['{{ value }}' => $code, '{{ url }}' => self::TEST_VALUE_SET_URL])
            ->setInvalidValue(new CodePrimitive(value: $code))
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }
}
