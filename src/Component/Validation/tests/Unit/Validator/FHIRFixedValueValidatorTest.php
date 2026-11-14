<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\PatCongregationExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<FHIRFixedValueValidator>
 */
final class FHIRFixedValueValidatorTest extends ConstraintValidatorTestCase
{
    use BindsValidatorContext;

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

    public function testNonNumericStringAgainstAFixedNumberEmitsErrorViolation(): void
    {
        $this->validator->validate('forty-two', new FHIRFixedValue(42));

        $this->buildViolation(FHIRFixedValueValidator::DEFAULT_MESSAGE)
            ->setParameters(['{{ value }}' => 'forty-two', '{{ fixed }}' => '42'])
            ->setInvalidValue('forty-two')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * A decimal is held as a string, while JSON decodes a whole-number fixed decimal (`fixedQuantity`
     * `{"value": 5}`) to an integer, so the two compare by value.
     */
    public function testDecimalStringMatchesAWholeNumberFixedValue(): void
    {
        $this->validator->validate((object) ['value' => '5.0'], new FHIRFixedValue(['value' => 5]));

        $this->assertNoViolation();
    }

    public function testStringableObjectMatchingConstraintValuePassesWithNoViolation(): void
    {
        $wrapper = new class ('http://ns.example.org') implements \Stringable {
            public function __construct(private readonly string $value)
            {
            }

            public function __toString(): string
            {
                return $this->value;
            }
        };

        $this->validator->validate($wrapper, new FHIRFixedValue('http://ns.example.org'));

        $this->assertNoViolation();
    }

    public function testStringableObjectNotMatchingConstraintValueEmitsErrorViolation(): void
    {
        $wrapper = new class ('http://wrong.org') implements \Stringable {
            public function __construct(private readonly string $value)
            {
            }

            public function __toString(): string
            {
                return $this->value;
            }
        };

        $this->validator->validate($wrapper, new FHIRFixedValue('http://ns.example.org'));

        $this->buildViolation(FHIRFixedValueValidator::DEFAULT_MESSAGE)
            ->setParameters(['{{ value }}' => 'http://wrong.org', '{{ fixed }}' => 'http://ns.example.org'])
            ->setInvalidValue($wrapper)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testComplexValueEqualToTheFixedValuePasses(): void
    {
        $this->validator->validate(
            (object) ['coding' => [(object) ['system' => 'http://loinc.org', 'code' => '35200-5']], 'extension' => []],
            new FHIRFixedValue(['coding' => [['system' => 'http://loinc.org', 'code' => '35200-5']]]),
        );

        $this->assertNoViolation();
    }

    public function testElementMissingFromTheInstanceIsReported(): void
    {
        $this->validator->validate((object) ['unit' => 'mmol/L'], new FHIRFixedValue(['value' => 4.5, 'unit' => 'mmol/L']));

        $this->buildViolation(FHIRFixedValueValidator::MISSING_ELEMENT_MESSAGE)
            ->setParameters(['{{ element }}' => 'value'])
            ->setInvalidValue(null)
            ->atPath('property.path.value')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testRepeatingElementWithADifferentItemCountIsReportedOnce(): void
    {
        $codings = [(object) ['code' => 'a'], (object) ['code' => 'b']];

        $this->validator->validate((object) ['coding' => $codings], new FHIRFixedValue(['coding' => [['code' => 'a']]]));

        $this->buildViolation(FHIRFixedValueValidator::COUNT_MESSAGE)
            ->setParameters(['{{ expected }}' => '1', '{{ actual }}' => '2', '{{ element }}' => 'coding'])
            ->setInvalidValue($codings)
            ->atPath('property.path.coding')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testFixedValueForARepeatingPropertyAppliesToEachItem(): void
    {
        $this->validator->validate(
            [(object) ['code' => 'a'], (object) ['code' => 'b']],
            new FHIRFixedValue(['code' => 'a']),
        );

        $this->buildViolation(FHIRFixedValueValidator::DEFAULT_MESSAGE)
            ->setParameters(['{{ value }}' => 'b', '{{ fixed }}' => 'a'])
            ->setInvalidValue('b')
            ->atPath('property.path[1].code')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testChoiceElementIsFoundUnderItsVariantName(): void
    {
        $this->validator->validate((object) ['value' => 'x'], new FHIRFixedValue(['valueString' => 'x']));

        $this->assertNoViolation();
    }

    public function testWholeNumbersBeyondFloatPrecisionStayDistinct(): void
    {
        $this->validator->validate('9007199254740993', new FHIRFixedValue(9007199254740992));

        $this->buildViolation(FHIRFixedValueValidator::DEFAULT_MESSAGE)
            ->setParameters(['{{ value }}' => '9007199254740993', '{{ fixed }}' => '9007199254740992'])
            ->setInvalidValue('9007199254740993')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * A generated extension holds its value under `valueString` and the inherited `value` alike; that
     * is one element, not an extra one.
     */
    public function testTypedExtensionValueAliasIsNotAnExtraElement(): void
    {
        $url = 'http://hl7.org/fhir/StructureDefinition/patient-congregation';

        $this->validator->validate(
            new PatCongregationExtension(valueString: new StringPrimitive(value: 'x')),
            new FHIRFixedValue(['url' => $url, 'valueString' => 'x']),
        );

        $this->assertNoViolation();
    }

    public function testChoiceVariantOfAnotherTypeDoesNotMatch(): void
    {
        $held = new StringPrimitive(value: 'x');

        $this->validator->validate((object) ['value' => $held], new FHIRFixedValue(['valueCode' => 'x']));

        $this->buildViolation(FHIRFixedValueValidator::MISSING_ELEMENT_MESSAGE)
            ->setParameters(['{{ element }}' => 'valueCode'])
            ->setInvalidValue(null)
            ->atPath('property.path.valueCode')
            ->setCode(FHIRViolationCode::ERROR)
            ->buildNextViolation(FHIRFixedValueValidator::EXTRA_ELEMENT_MESSAGE)
            ->setParameters(['{{ element }}' => 'value'])
            ->setInvalidValue($held)
            ->atPath('property.path.value')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testPrimitiveExtensionTheFixedValueLacksIsReported(): void
    {
        $extensions = [new Extension(url: 'http://example.org/note')];

        $this->validator->validate(
            (object) ['code' => new CodePrimitive(value: 'x', extension: $extensions)],
            new FHIRFixedValue(['code' => 'x']),
        );

        $this->buildViolation(FHIRFixedValueValidator::EXTRA_ELEMENT_MESSAGE)
            ->setParameters(['{{ element }}' => 'extension'])
            ->setInvalidValue($extensions)
            ->atPath('property.path.code.extension')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testPrimitiveExtensionTheFixedValueRequiresIsReportedMissing(): void
    {
        $this->validator->validate(
            (object) ['code' => new CodePrimitive(value: 'x')],
            new FHIRFixedValue(['code' => 'x', '_code' => ['extension' => [['url' => 'http://example.org/note']]]]),
        );

        $this->buildViolation(FHIRFixedValueValidator::COUNT_MESSAGE)
            ->setParameters(['{{ expected }}' => '1', '{{ actual }}' => '0', '{{ element }}' => 'extension'])
            ->setInvalidValue(null)
            ->atPath('property.path.code.extension')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testRegistryOverrideIsUsedInViolationMessage(): void
    {
        $registry = new FHIRValidationMessageRegistry();
        $registry->setOverride('FHIRFixedValue', 'Custom fixed value message.');

        $validator = new FHIRFixedValueValidator($registry);
        $this->bindContext($validator);

        $validator->validate('wrong', new FHIRFixedValue('right'));

        $this->buildViolation('Custom fixed value message.')
            ->setParameters(['{{ value }}' => 'wrong', '{{ fixed }}' => 'right'])
            ->setInvalidValue('wrong')
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }
}
