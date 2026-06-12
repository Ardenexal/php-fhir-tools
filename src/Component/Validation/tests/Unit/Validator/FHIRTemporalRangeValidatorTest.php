<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTemporalRange;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTemporalRangeValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @extends ConstraintValidatorTestCase<FHIRTemporalRangeValidator>
 */
final class FHIRTemporalRangeValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): FHIRTemporalRangeValidator
    {
        return new FHIRTemporalRangeValidator();
    }

    // --- null / empty ---

    public function testNullValueProducesNoViolation(): void
    {
        $this->validator->validate(null, new FHIRTemporalRange(minValue: '2000-01-01', maxValue: null, temporalType: 'date'));

        $this->assertNoViolation();
    }

    public function testEmptyStringProducesNoViolation(): void
    {
        $this->validator->validate('', new FHIRTemporalRange(minValue: '2000-01-01', maxValue: null, temporalType: 'date'));

        $this->assertNoViolation();
    }

    // --- date within bounds ---

    public function testDateWithinBoundsProducesNoViolation(): void
    {
        $this->validator->validate('2023-06-15', new FHIRTemporalRange(minValue: '2000-01-01', maxValue: '2099-12-31', temporalType: 'date'));

        $this->assertNoViolation();
    }

    public function testDateEqualToMinBoundProducesNoViolation(): void
    {
        $this->validator->validate('2000-01-01', new FHIRTemporalRange(minValue: '2000-01-01', maxValue: null, temporalType: 'date'));

        $this->assertNoViolation();
    }

    public function testDateEqualToMaxBoundProducesNoViolation(): void
    {
        $this->validator->validate('2099-12-31', new FHIRTemporalRange(minValue: null, maxValue: '2099-12-31', temporalType: 'date'));

        $this->assertNoViolation();
    }

    // --- date out of bounds ---

    public function testDateBeforeMinProducesError(): void
    {
        $this->validator->validate('1999-12-31', new FHIRTemporalRange(minValue: '2000-01-01', maxValue: null, temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is before the minimum {{ min }}.')
            ->setParameters(['{{ value }}' => '1999-12-31', '{{ min }}' => '2000-01-01'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testDateAfterMaxProducesError(): void
    {
        $this->validator->validate('2100-01-01', new FHIRTemporalRange(minValue: null, maxValue: '2099-12-31', temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is after the maximum {{ max }}.')
            ->setParameters(['{{ value }}' => '2100-01-01', '{{ max }}' => '2099-12-31'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // --- partial date expansion ---

    public function testPartialYearExpandsToStartOfYearForMinComparison(): void
    {
        // '2023' expands to 2023-01-01 for min comparison; 2022-06-15 < 2023-01-01 → error
        $this->validator->validate('2022', new FHIRTemporalRange(minValue: '2022-06-15', maxValue: null, temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is before the minimum {{ min }}.')
            ->setParameters(['{{ value }}' => '2022', '{{ min }}' => '2022-06-15'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testPartialYearExpandsToEndOfYearForMaxComparison(): void
    {
        // '2023' expands to 2023-12-31 for max comparison; 2023-12-31 > 2023-06-01 → error
        $this->validator->validate('2023', new FHIRTemporalRange(minValue: null, maxValue: '2023-06-01', temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is after the maximum {{ max }}.')
            ->setParameters(['{{ value }}' => '2023', '{{ max }}' => '2023-06-01'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testPartialYearAboveEarlierBoundProducesNoViolation(): void
    {
        // '2023' min-side: 2023-01-01 >= 2022-06-15 → ok; max-side: 2023-12-31 <= 2099-12-31 → ok
        $this->validator->validate('2023', new FHIRTemporalRange(minValue: '2022-06-15', maxValue: '2099-12-31', temporalType: 'date'));

        $this->assertNoViolation();
    }

    public function testPartialYearMonthExpandedForMaxComparison(): void
    {
        // '2023-01' max-side: 2023-01-31 > 2023-01-15 → error
        $this->validator->validate('2023-01', new FHIRTemporalRange(minValue: null, maxValue: '2023-01-15', temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is after the maximum {{ max }}.')
            ->setParameters(['{{ value }}' => '2023-01', '{{ max }}' => '2023-01-15'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // --- partial MAX BOUND expansion (the bound itself is partial) ---

    public function testPartialYearMaxBoundExpandsToEndOfYear(): void
    {
        // max bound '2099' expands to 2099-12-31; value 2099-06-15 <= 2099-12-31 → ok
        $this->validator->validate('2099-06-15', new FHIRTemporalRange(minValue: null, maxValue: '2099', temporalType: 'date'));

        $this->assertNoViolation();
    }

    public function testWholeYearValueWithinWholeYearMaxBoundProducesNoViolation(): void
    {
        // both bound and value '2099' expand to 2099-12-31; 2099-12-31 <= 2099-12-31 → ok
        $this->validator->validate('2099', new FHIRTemporalRange(minValue: null, maxValue: '2099', temporalType: 'date'));

        $this->assertNoViolation();
    }

    public function testValueAfterPartialYearMaxBoundProducesError(): void
    {
        // max bound '2099' expands to 2099-12-31; value 2100-01-01 > 2099-12-31 → error
        $this->validator->validate('2100-01-01', new FHIRTemporalRange(minValue: null, maxValue: '2099', temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is after the maximum {{ max }}.')
            ->setParameters(['{{ value }}' => '2100-01-01', '{{ max }}' => '2099'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testValueAfterPartialMonthMaxBoundProducesError(): void
    {
        // max bound '2022-06' expands to 2022-06-30; value 2022-07-01 > 2022-06-30 → error
        $this->validator->validate('2022-07-01', new FHIRTemporalRange(minValue: null, maxValue: '2022-06', temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is after the maximum {{ max }}.')
            ->setParameters(['{{ value }}' => '2022-07-01', '{{ max }}' => '2022-06'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testLastDayOfMonthWithinPartialMonthMaxBoundProducesNoViolation(): void
    {
        // max bound '2022-06' expands to 2022-06-30; value 2022-06-30 <= 2022-06-30 → ok.
        // Pre-fix this was non-deterministic: the full-date value parsed at the current
        // wall-clock time, so on the same calendar day it could exceed the midnight max bound.
        $this->validator->validate('2022-06-30', new FHIRTemporalRange(minValue: null, maxValue: '2022-06', temporalType: 'date'));

        $this->assertNoViolation();
    }

    // --- time ---

    public function testTimeWithinBoundsProducesNoViolation(): void
    {
        $this->validator->validate('10:00:00', new FHIRTemporalRange(minValue: '09:00:00', maxValue: '17:00:00', temporalType: 'time'));

        $this->assertNoViolation();
    }

    public function testTimeBeforeMinProducesError(): void
    {
        $this->validator->validate('08:59:59', new FHIRTemporalRange(minValue: '09:00:00', maxValue: null, temporalType: 'time'));

        $this->buildViolation('The value {{ value }} is before the minimum {{ min }}.')
            ->setParameters(['{{ value }}' => '08:59:59', '{{ min }}' => '09:00:00'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testTimeAfterMaxProducesError(): void
    {
        $this->validator->validate('17:00:01', new FHIRTemporalRange(minValue: null, maxValue: '17:00:00', temporalType: 'time'));

        $this->buildViolation('The value {{ value }} is after the maximum {{ max }}.')
            ->setParameters(['{{ value }}' => '17:00:01', '{{ max }}' => '17:00:00'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // --- instant ---

    public function testInstantWithinBoundsProducesNoViolation(): void
    {
        $this->validator->validate(
            '2023-06-15T10:30:00+00:00',
            new FHIRTemporalRange(minValue: '2000-01-01T00:00:00+00:00', maxValue: '2099-12-31T23:59:59+00:00', temporalType: 'instant'),
        );

        $this->assertNoViolation();
    }

    public function testInstantBeforeMinProducesError(): void
    {
        $this->validator->validate(
            '1999-12-31T23:59:59+00:00',
            new FHIRTemporalRange(minValue: '2000-01-01T00:00:00+00:00', maxValue: null, temporalType: 'instant'),
        );

        $this->buildViolation('The value {{ value }} is before the minimum {{ min }}.')
            ->setParameters(['{{ value }}' => '1999-12-31T23:59:59+00:00', '{{ min }}' => '2000-01-01T00:00:00+00:00'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    // --- unparseable input ---

    public function testUnparseableDateValueProducesError(): void
    {
        $this->validator->validate('not-a-date', new FHIRTemporalRange(minValue: '2000-01-01', maxValue: null, temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is not a valid FHIR {{ type }} string.')
            ->setParameters(['{{ value }}' => 'not-a-date', '{{ type }}' => 'date'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testUnparseableMinBoundProducesWarning(): void
    {
        $this->validator->validate('2023-06-15', new FHIRTemporalRange(minValue: 'BAD-BOUND', maxValue: null, temporalType: 'date'));

        $this->buildViolation('The configured {{ side }} bound {{ bound }} is not a valid FHIR {{ type }} string.')
            ->setParameters(['{{ side }}' => 'minimum', '{{ bound }}' => 'BAD-BOUND', '{{ type }}' => 'date'])
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    public function testUnparseableMaxBoundProducesWarning(): void
    {
        $this->validator->validate('2023-06-15', new FHIRTemporalRange(minValue: null, maxValue: 'BAD-BOUND', temporalType: 'date'));

        $this->buildViolation('The configured {{ side }} bound {{ bound }} is not a valid FHIR {{ type }} string.')
            ->setParameters(['{{ side }}' => 'maximum', '{{ bound }}' => 'BAD-BOUND', '{{ type }}' => 'date'])
            ->setCode(FHIRViolationCode::WARNING)
            ->assertRaised();
    }

    // --- B4: real \Stringable model objects (the production path) must be enforced, not no-op'd ---

    public function testStringableModelObjectBelowMinProducesError(): void
    {
        // A real primitive wrapper (\Stringable), as a deserialized model property would hold —
        // NOT a raw string. Pre-fix the validator no-op'd on this and the constraint went unenforced.
        $value = new DatePrimitive(value: FHIRDate::parse('1999-12-31'));

        $this->validator->validate($value, new FHIRTemporalRange(minValue: '2000-01-01', maxValue: null, temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is before the minimum {{ min }}.')
            ->setParameters(['{{ value }}' => '1999-12-31', '{{ min }}' => '2000-01-01'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testStringableModelObjectAboveMaxProducesError(): void
    {
        $value = new DatePrimitive(value: FHIRDate::parse('2100-01-01'));

        $this->validator->validate($value, new FHIRTemporalRange(minValue: null, maxValue: '2099-12-31', temporalType: 'date'));

        $this->buildViolation('The value {{ value }} is after the maximum {{ max }}.')
            ->setParameters(['{{ value }}' => '2100-01-01', '{{ max }}' => '2099-12-31'])
            ->setCode(FHIRViolationCode::ERROR)
            ->assertRaised();
    }

    public function testStringableModelObjectWithinBoundsProducesNoViolation(): void
    {
        $value = new DatePrimitive(value: FHIRDate::parse('2023-06-15'));

        $this->validator->validate($value, new FHIRTemporalRange(minValue: '2000-01-01', maxValue: '2099-12-31', temporalType: 'date'));

        $this->assertNoViolation();
    }
}
