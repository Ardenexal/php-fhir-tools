<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRQuantityRange;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRQuantityRangeValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<FHIRQuantityRangeValidator>
 */
final class FHIRQuantityRangeValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): FHIRQuantityRangeValidator
    {
        return new FHIRQuantityRangeValidator();
    }

    private function makeQuantity(?string $value, ?string $system, ?string $code, mixed $comparator = null): object
    {
        return new class ($value, $system, $code, $comparator) {
            public readonly mixed $value;

            public readonly mixed $system;

            public readonly mixed $code;

            public readonly mixed $comparator;

            public function __construct(mixed $value, mixed $system, mixed $code, mixed $comparator)
            {
                $this->value      = $value;
                $this->system     = $system !== null ? new class ($system) implements \Stringable {
                    public function __construct(private readonly string $v)
                    {
                    }

                    public function __toString(): string
                    {
                        return $this->v;
                    }
                } : null;
                $this->code       = $code !== null ? new class ($code) implements \Stringable {
                    public function __construct(private readonly string $v)
                    {
                    }

                    public function __toString(): string
                    {
                        return $this->v;
                    }
                } : null;
                $this->comparator = $comparator;
            }
        };
    }

    private function mgBound(float $value): array
    {
        return ['value' => $value, 'system' => 'http://unitsofmeasure.org', 'code' => 'mg'];
    }

    // --- null / non-object ---

    public function testNullValueProducesNoViolation(): void
    {
        $this->validator->validate(null, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->assertNoViolation();
    }

    public function testNonObjectValueProducesNoViolation(): void
    {
        $this->validator->validate('not-an-object', new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->assertNoViolation();
    }

    public function testNullNumericValueProducesNoViolation(): void
    {
        $qty = $this->makeQuantity(null, 'http://unitsofmeasure.org', 'mg');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->assertNoViolation();
    }

    // --- within bounds ---

    public function testValueWithinBoundsProducesNoViolation(): void
    {
        $qty = $this->makeQuantity('500', 'http://unitsofmeasure.org', 'mg');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: $this->mgBound(1000.0)));

        $this->assertNoViolation();
    }

    public function testValueEqualToMinBoundProducesNoViolation(): void
    {
        $qty = $this->makeQuantity('0', 'http://unitsofmeasure.org', 'mg');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->assertNoViolation();
    }

    public function testValueEqualToMaxBoundProducesNoViolation(): void
    {
        $qty = $this->makeQuantity('1000', 'http://unitsofmeasure.org', 'mg');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: null, maxValue: $this->mgBound(1000.0)));

        $this->assertNoViolation();
    }

    // --- out of bounds (same unit) ---

    public function testValueBelowMinProducesError(): void
    {
        $qty = $this->makeQuantity('-1', 'http://unitsofmeasure.org', 'mg');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->buildViolation('The value {{ value }} is below the minimum {{ min }} {{ unit }}.')
            ->setParameters(['{{ value }}' => '-1', '{{ min }}' => '0', '{{ unit }}' => 'mg'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testValueAboveMaxProducesError(): void
    {
        $qty = $this->makeQuantity('1001', 'http://unitsofmeasure.org', 'mg');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: null, maxValue: $this->mgBound(1000.0)));

        $this->buildViolation('The value {{ value }} exceeds the maximum {{ max }} {{ unit }}.')
            ->setParameters(['{{ value }}' => '1001', '{{ max }}' => '1000', '{{ unit }}' => 'mg'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // --- cross-unit (warning) ---

    public function testCrossUnitProducesWarning(): void
    {
        $qty = $this->makeQuantity('5', 'http://unitsofmeasure.org', 'g');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->buildViolation('Cannot verify quantity bound: instance unit {{ instanceCode }} differs from bound unit {{ boundCode }}.')
            ->setParameters(['{{ instanceCode }}' => 'g', '{{ boundCode }}' => 'mg'])
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    // --- missing unit context (warning) ---

    public function testMissingInstanceSystemProducesWarning(): void
    {
        $qty = $this->makeQuantity('5', null, 'mg');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->buildViolation('Cannot verify quantity bound: instance is missing system or code.')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    public function testMissingInstanceCodeProducesWarning(): void
    {
        $qty = $this->makeQuantity('5', 'http://unitsofmeasure.org', null);
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->buildViolation('Cannot verify quantity bound: instance is missing system or code.')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    // --- malformed bound (warning) ---

    public function testMalformedMinBoundMissingSystemProducesWarning(): void
    {
        $qty   = $this->makeQuantity('5', 'http://unitsofmeasure.org', 'mg');
        $bound = ['value' => 0.0, 'system' => null, 'code' => 'mg'];
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $bound, maxValue: null));

        $this->buildViolation('Cannot verify quantity {{ side }} bound: bound is missing system or code.')
            ->setParameter('{{ side }}', 'minimum')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    public function testMalformedMaxBoundMissingCodeProducesWarning(): void
    {
        $qty   = $this->makeQuantity('5', 'http://unitsofmeasure.org', 'mg');
        $bound = ['value' => 1000.0, 'system' => 'http://unitsofmeasure.org', 'code' => null];
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: null, maxValue: $bound));

        $this->buildViolation('Cannot verify quantity {{ side }} bound: bound is missing system or code.')
            ->setParameter('{{ side }}', 'maximum')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    // --- instance comparator (warning) ---

    public function testInstanceWithComparatorProducesWarning(): void
    {
        $comparatorEnum = new class () {
            public string $value = '<';
        };
        $qty = $this->makeQuantity('5', 'http://unitsofmeasure.org', 'mg', $comparatorEnum);
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: $this->mgBound(0.0), maxValue: null));

        $this->buildViolation('Cannot verify quantity bound: instance value has a comparator and is not a precise measurement.')
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    // --- both bounds null ---

    public function testBothBoundsNullProducesNoViolation(): void
    {
        $qty = $this->makeQuantity('5', 'http://unitsofmeasure.org', 'mg');
        $this->validator->validate($qty, new FHIRQuantityRange(minValue: null, maxValue: null));

        $this->assertNoViolation();
    }
}
