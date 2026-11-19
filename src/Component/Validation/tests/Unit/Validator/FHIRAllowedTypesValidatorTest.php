<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRAllowedTypes;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Age;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRAllowedTypesValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<FHIRAllowedTypesValidator>
 */
#[CoversClass(FHIRAllowedTypesValidator::class)]
final class FHIRAllowedTypesValidatorTest extends ConstraintValidatorTestCase
{
    use BindsValidatorContext;

    protected function createValidator(): FHIRAllowedTypesValidator
    {
        return new FHIRAllowedTypesValidator(new FHIRValidationMessageRegistry());
    }

    /**
     * @return iterable<string, array{0: mixed, 1: string}>
     */
    public static function valuesOfOneType(): iterable
    {
        yield 'string primitive' => [new StringPrimitive(value: 'x'), 'string'];
        yield 'subclassed primitive' => [new PositiveIntPrimitive(value: 1), 'positiveInt'];
        yield 'complex type' => [new Reference(reference: 'Patient/1'), 'Reference'];
        yield 'subclassed complex type' => [new Age(value: '1'), 'Age'];
        yield 'bare boolean' => [true, 'boolean'];
        yield 'bare integer' => [5, 'integer'];
        yield 'bare decimal' => ['1.50', 'decimal'];
    }

    #[DataProvider('valuesOfOneType')]
    public function testAllowedTypePasses(mixed $value, string $type): void
    {
        $this->validator->validate($value, new FHIRAllowedTypes([$type]));

        $this->assertNoViolation();
    }

    #[DataProvider('valuesOfOneType')]
    public function testOtherTypeIsReportedByItsFhirName(mixed $value, string $type): void
    {
        $this->validator->validate($value, new FHIRAllowedTypes(['CodeableConcept', 'Period']));

        $this->buildViolation(FHIRAllowedTypesValidator::DEFAULT_MESSAGE)
            ->setParameters(['{{ type }}' => $type, '{{ allowed }}' => 'CodeableConcept, Period'])
            ->setInvalidValue($value)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    /**
     * An Age is a Quantity in PHP, but `value[x] only Quantity` does not allow `valueAge`.
     */
    public function testSubclassDoesNotPassForItsParentType(): void
    {
        $age = new Age(value: '1');

        $this->validator->validate($age, new FHIRAllowedTypes(['Quantity']));

        $this->buildViolation(FHIRAllowedTypesValidator::DEFAULT_MESSAGE)
            ->setParameters(['{{ type }}' => 'Age', '{{ allowed }}' => 'Quantity'])
            ->setInvalidValue($age)
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testQuantityPassesForQuantity(): void
    {
        $this->validator->validate(new Quantity(value: '1'), new FHIRAllowedTypes(['Quantity']));

        $this->assertNoViolation();
    }

    /**
     * `"valueBoolean": "abc"` is held as a bare string. Its type cannot be told, and the format
     * check already reports the value, so it is not also reported as a disallowed decimal.
     */
    public function testBareStringOfUnknowableTypeIsLeftAlone(): void
    {
        $this->validator->validate('abc', new FHIRAllowedTypes(['boolean']));
        // A model built in code may hold a plain string for a string-like type
        $this->validator->validate('42', new FHIRAllowedTypes(['string']));
        $this->validator->validate(new \stdClass(), new FHIRAllowedTypes(['boolean']));
        $this->validator->validate(null, new FHIRAllowedTypes(['boolean']));

        $this->assertNoViolation();
    }
}
