<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\FHIRPath\Evaluator;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\ComparisonService;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use PHPUnit\Framework\TestCase;

final class ComparisonServiceTest extends TestCase
{
    private ComparisonService $service;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->evaluator = new FHIRPathEvaluator();
        $this->service   = new ComparisonService($this->evaluator);
    }

    public function testEmptyCollectionsReturnEmpty(): void
    {
        $empty = Collection::empty();
        $value = Collection::single(1);

        $result1 = $this->service->compareEquality($empty, $empty, '=');
        $result2 = $this->service->compareEquality($empty, $value, '=');
        $result3 = $this->service->compareEquality($value, $empty, '=');

        self::assertTrue($result1->isEmpty());
        self::assertTrue($result2->isEmpty());
        self::assertTrue($result3->isEmpty());
    }

    public function testSingleValueEquality(): void
    {
        $one     = Collection::single(1);
        $two     = Collection::single(2);
        $alsoOne = Collection::single(1);

        $resultEqual    = $this->service->compareEquality($one, $alsoOne, '=');
        $resultNotEqual = $this->service->compareEquality($one, $two, '=');

        self::assertTrue($resultEqual->isSingle());
        self::assertTrue($resultEqual->first());
        self::assertTrue($resultNotEqual->isSingle());
        self::assertFalse($resultNotEqual->first());
    }

    public function testCollectionEqualityOrderIndependent(): void
    {
        $col1 = Collection::from([1, 2, 3]);
        $col2 = Collection::from([3, 2, 1]);
        $col3 = Collection::from([1, 2, 4]);

        $resultEqual    = $this->service->compareEquality($col1, $col2, '=');
        $resultNotEqual = $this->service->compareEquality($col1, $col3, '=');

        self::assertTrue($resultEqual->isSingle());
        self::assertTrue($resultEqual->first());
        self::assertTrue($resultNotEqual->isSingle());
        self::assertFalse($resultNotEqual->first());
    }

    public function testCollectionEqualityDifferentSizes(): void
    {
        $col1 = Collection::from([1, 2]);
        $col2 = Collection::from([1, 2, 3]);

        $result = $this->service->compareEquality($col1, $col2, '=');

        self::assertTrue($result->isSingle());
        self::assertFalse($result->first());
    }

    public function testNotEqualsOperator(): void
    {
        $one     = Collection::single(1);
        $two     = Collection::single(2);
        $alsoOne = Collection::single(1);

        $resultNotEqual = $this->service->compareEquality($one, $two, '!=');
        $resultEqual    = $this->service->compareEquality($one, $alsoOne, '!=');

        self::assertTrue($resultNotEqual->isSingle());
        self::assertTrue($resultNotEqual->first());
        self::assertTrue($resultEqual->isSingle());
        self::assertFalse($resultEqual->first());
    }

    public function testCollectionNotEquals(): void
    {
        $col1 = Collection::from([1, 2, 3]);
        $col2 = Collection::from([3, 2, 1]);
        $col3 = Collection::from([1, 2, 4]);

        $resultEqual    = $this->service->compareEquality($col1, $col2, '!=');
        $resultNotEqual = $this->service->compareEquality($col1, $col3, '!=');

        self::assertTrue($resultEqual->isSingle());
        self::assertFalse($resultEqual->first());
        self::assertTrue($resultNotEqual->isSingle());
        self::assertTrue($resultNotEqual->first());
    }

    public function testDateTimePrecisionMatching(): void
    {
        $date1 = Collection::single('@2018-03');
        $date2 = Collection::single('@2018-03');

        $result = $this->service->compareEquality($date1, $date2, '=');

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testDateTimePrecisionMismatch(): void
    {
        $dateMonth = Collection::single('@2018-03');
        $dateDay   = Collection::single('@2018-03-01');

        $result = $this->service->compareEquality($dateMonth, $dateDay, '=');

        self::assertTrue($result->isEmpty());
    }

    public function testDateTimeDifferentPrecisionsIncomparable(): void
    {
        $date1 = Collection::single('@2012-04-15');
        $date2 = Collection::single('@2012-04-15T10:00:00');

        $result = $this->service->compareEquality($date1, $date2, '=');

        self::assertTrue($result->isEmpty());
    }

    public function testDateTimeEqualityWithSamePrecision(): void
    {
        $date1 = Collection::single('@2018-03-01');
        $date2 = Collection::single('@2018-03-01');
        $date3 = Collection::single('@2018-03-02');

        $resultEqual    = $this->service->compareEquality($date1, $date2, '=');
        $resultNotEqual = $this->service->compareEquality($date1, $date3, '=');

        self::assertTrue($resultEqual->isSingle());
        self::assertTrue($resultEqual->first());
        self::assertTrue($resultNotEqual->isSingle());
        self::assertFalse($resultNotEqual->first());
    }

    public function testEquivalenceOperatorNumericTypeNormalization(): void
    {
        $int   = Collection::single(1);
        $float = Collection::single(1.0);

        $resultEquivalence = $this->service->compareEquality($int, $float, '~');
        $resultEquality    = $this->service->compareEquality($int, $float, '=');

        // Equivalence should normalize types: 1 ~ 1.0 = true
        self::assertTrue($resultEquivalence->isSingle());
        self::assertTrue($resultEquivalence->first());

        // Equality should preserve types: 1 = 1.0 = false (strict comparison)
        self::assertTrue($resultEquality->isSingle());
        self::assertFalse($resultEquality->first());
    }

    public function testNotEquivalentOperator(): void
    {
        $int   = Collection::single(1);
        $float = Collection::single(1.0);
        $two   = Collection::single(2);

        $resultSame      = $this->service->compareEquality($int, $float, '!~');
        $resultDifferent = $this->service->compareEquality($int, $two, '!~');

        // 1 !~ 1.0 should be false (they are equivalent)
        self::assertTrue($resultSame->isSingle());
        self::assertFalse($resultSame->first());

        // 1 !~ 2 should be true (they are not equivalent)
        self::assertTrue($resultDifferent->isSingle());
        self::assertTrue($resultDifferent->first());
    }

    public function testMixedTypesInCollections(): void
    {
        $col1 = Collection::from([1, 'a', true]);
        $col2 = Collection::from(['a', 1, true]);
        $col3 = Collection::from([1, 'a', false]);

        $resultEqual    = $this->service->compareEquality($col1, $col2, '=');
        $resultNotEqual = $this->service->compareEquality($col1, $col3, '=');

        self::assertTrue($resultEqual->isSingle());
        self::assertTrue($resultEqual->first());
        self::assertTrue($resultNotEqual->isSingle());
        self::assertFalse($resultNotEqual->first());
    }

    public function testStringEquality(): void
    {
        $str1 = Collection::single('hello');
        $str2 = Collection::single('hello');
        $str3 = Collection::single('world');

        $resultEqual    = $this->service->compareEquality($str1, $str2, '=');
        $resultNotEqual = $this->service->compareEquality($str1, $str3, '=');

        self::assertTrue($resultEqual->isSingle());
        self::assertTrue($resultEqual->first());
        self::assertTrue($resultNotEqual->isSingle());
        self::assertFalse($resultNotEqual->first());
    }

    public function testBooleanEquality(): void
    {
        $true1  = Collection::single(true);
        $true2  = Collection::single(true);
        $false1 = Collection::single(false);

        $resultEqual    = $this->service->compareEquality($true1, $true2, '=');
        $resultNotEqual = $this->service->compareEquality($true1, $false1, '=');

        self::assertTrue($resultEqual->isSingle());
        self::assertTrue($resultEqual->first());
        self::assertTrue($resultNotEqual->isSingle());
        self::assertFalse($resultNotEqual->first());
    }

    public function testOrderingWithSingleValues(): void
    {
        $one = Collection::single(1);
        $two = Collection::single(2);

        $resultLessThan    = $this->service->compareOrdering($one, $two, fn ($a, $b) => $a < $b);
        $resultGreaterThan = $this->service->compareOrdering($two, $one, fn ($a, $b) => $a > $b);

        self::assertTrue($resultLessThan->isSingle());
        self::assertTrue($resultLessThan->first());
        self::assertTrue($resultGreaterThan->isSingle());
        self::assertTrue($resultGreaterThan->first());
    }

    public function testOrderingWithMultipleValuesReturnsEmpty(): void
    {
        $col1 = Collection::from([1, 2]);
        $col2 = Collection::from([3, 4]);

        $result = $this->service->compareOrdering($col1, $col2, fn ($a, $b) => $a < $b);

        self::assertTrue($result->isEmpty());
    }

    public function testOrderingWithEmptyReturnsEmpty(): void
    {
        $empty = Collection::empty();
        $value = Collection::single(1);

        $result1 = $this->service->compareOrdering($empty, $value, fn ($a, $b) => $a < $b);
        $result2 = $this->service->compareOrdering($value, $empty, fn ($a, $b) => $a < $b);

        self::assertTrue($result1->isEmpty());
        self::assertTrue($result2->isEmpty());
    }

    public function testOrderingDateTimePrecisionMismatch(): void
    {
        $dateMonth = Collection::single('@2018-03');
        $dateDay   = Collection::single('@2018-03-01');

        $result = $this->service->compareOrdering($dateMonth, $dateDay, fn ($a, $b) => $a < $b);

        self::assertTrue($result->isEmpty());
    }

    public function testOrderingDateTimeSamePrecision(): void
    {
        $date1 = Collection::single('@2018-03-01');
        $date2 = Collection::single('@2018-03-02');

        $resultLess    = $this->service->compareOrdering($date1, $date2, fn ($a, $b) => $a < $b);
        $resultGreater = $this->service->compareOrdering($date2, $date1, fn ($a, $b) => $a > $b);

        self::assertTrue($resultLess->isSingle());
        self::assertTrue($resultLess->first());
        self::assertTrue($resultGreater->isSingle());
        self::assertTrue($resultGreater->first());
    }

    public function testCollectionWithDuplicates(): void
    {
        // Note: Collection union() already handles duplicates, but test the comparison
        $col1 = Collection::from([1, 1, 2]);
        $col2 = Collection::from([1, 2, 2]);

        // These should NOT be equal because they have different duplicate patterns
        $result = $this->service->compareEquality($col1, $col2, '=');

        self::assertTrue($result->isSingle());
        self::assertFalse($result->first());
    }

    public function testDateTimeWithTimezone(): void
    {
        $date1 = Collection::single('@2018-03-01T10:00:00Z');
        $date2 = Collection::single('@2018-03-01T10:00:00Z');
        $date3 = Collection::single('@2018-03-01T10:00:00+01:00');

        $resultSameTimezone      = $this->service->compareEquality($date1, $date2, '=');
        $resultDifferentTimezone = $this->service->compareEquality($date1, $date3, '=');

        self::assertTrue($resultSameTimezone->isSingle());
        self::assertTrue($resultSameTimezone->first());
        self::assertTrue($resultDifferentTimezone->isSingle());
        self::assertFalse($resultDifferentTimezone->first());
    }

    public function testTimeOnlyLiterals(): void
    {
        $time1 = Collection::single('@T10:30:00');
        $time2 = Collection::single('@T10:30:00');
        $time3 = Collection::single('@T11:30:00');

        $resultEqual    = $this->service->compareEquality($time1, $time2, '=');
        $resultNotEqual = $this->service->compareEquality($time1, $time3, '=');

        self::assertTrue($resultEqual->isSingle());
        self::assertTrue($resultEqual->first());
        self::assertTrue($resultNotEqual->isSingle());
        self::assertFalse($resultNotEqual->first());
    }

    public function testZeroFractionalSecondsNormalization(): void
    {
        // .0, .00, .000 should be treated as second precision (6), not millisecond (7)
        $time1 = Collection::single('@T10:30:00');
        $time2 = Collection::single('@T10:30:00.0');
        $time3 = Collection::single('@T10:30:00.00');
        $time4 = Collection::single('@T10:30:00.000');

        // All should be equal (same precision after normalization)
        $result1 = $this->service->compareEquality($time1, $time2, '=');
        $result2 = $this->service->compareEquality($time1, $time3, '=');
        $result3 = $this->service->compareEquality($time1, $time4, '=');
        $result4 = $this->service->compareEquality($time2, $time3, '=');

        self::assertTrue($result1->isSingle());
        self::assertTrue($result1->first(), '@T10:30:00 should equal @T10:30:00.0');
        self::assertTrue($result2->isSingle());
        self::assertTrue($result2->first(), '@T10:30:00 should equal @T10:30:00.00');
        self::assertTrue($result3->isSingle());
        self::assertTrue($result3->first(), '@T10:30:00 should equal @T10:30:00.000');
        self::assertTrue($result4->isSingle());
        self::assertTrue($result4->first(), '@T10:30:00.0 should equal @T10:30:00.00');
    }

    public function testZeroFractionalSecondsWithDateTime(): void
    {
        // Same test with full datetime literals
        $date1 = Collection::single('@2018-03-01T10:30:00');
        $date2 = Collection::single('@2018-03-01T10:30:00.0');
        $date3 = Collection::single('@2018-03-01T10:30:00.000');

        $result1 = $this->service->compareEquality($date1, $date2, '=');
        $result2 = $this->service->compareEquality($date1, $date3, '=');

        self::assertTrue($result1->isSingle());
        self::assertTrue($result1->first());
        self::assertTrue($result2->isSingle());
        self::assertTrue($result2->first());
    }

    public function testNonZeroFractionalSecondsRemainMillisecondPrecision(): void
    {
        // Non-zero fractional seconds should still be incomparable with second precision
        $time1 = Collection::single('@T10:30:00');
        $time2 = Collection::single('@T10:30:00.001');
        $time3 = Collection::single('@T10:30:00.123');

        // Different precisions (6 vs 7) should return empty
        $result1 = $this->service->compareEquality($time1, $time2, '=');
        $result2 = $this->service->compareEquality($time1, $time3, '=');

        self::assertTrue($result1->isEmpty(), '@T10:30:00 vs @T10:30:00.001 should be incomparable');
        self::assertTrue($result2->isEmpty(), '@T10:30:00 vs @T10:30:00.123 should be incomparable');
    }

    public function testZeroFractionalSecondsOrderingOperators(): void
    {
        // Test with ordering operators
        $time1 = Collection::single('@T10:30:00');
        $time2 = Collection::single('@T10:30:00.0');

        // Should be comparable and equal (both are second precision after normalization)
        $resultLess         = $this->service->compareOrdering($time1, $time2, fn ($a, $b) => $a < $b);
        $resultLessOrEqual  = $this->service->compareOrdering($time1, $time2, fn ($a, $b) => $a <= $b);
        $resultGreater      = $this->service->compareOrdering($time1, $time2, fn ($a, $b) => $a > $b);
        $resultGreaterEqual = $this->service->compareOrdering($time1, $time2, fn ($a, $b) => $a >= $b);

        self::assertTrue($resultLess->isSingle());
        self::assertFalse($resultLess->first(), '@T10:30:00 < @T10:30:00.0 should be false');

        self::assertTrue($resultLessOrEqual->isSingle());
        self::assertTrue($resultLessOrEqual->first(), '@T10:30:00 <= @T10:30:00.0 should be true');

        self::assertTrue($resultGreater->isSingle());
        self::assertFalse($resultGreater->first(), '@T10:30:00 > @T10:30:00.0 should be false');

        self::assertTrue($resultGreaterEqual->isSingle());
        self::assertTrue($resultGreaterEqual->first(), '@T10:30:00 >= @T10:30:00.0 should be true');
    }

    public function testZeroFractionalSecondsOrderingWithDateTime(): void
    {
        // Same test with full datetime literals
        $date1 = Collection::single('@2018-03-01T10:30:00');
        $date2 = Collection::single('@2018-03-01T10:30:00.0');

        $resultLess        = $this->service->compareOrdering($date1, $date2, fn ($a, $b) => $a < $b);
        $resultGreaterEq   = $this->service->compareOrdering($date1, $date2, fn ($a, $b) => $a >= $b);

        self::assertTrue($resultLess->isSingle());
        self::assertFalse($resultLess->first());
        self::assertTrue($resultGreaterEq->isSingle());
        self::assertTrue($resultGreaterEq->first());
    }

    public function testQuantityEqualityWithSameUnit(): void
    {
        $left = Collection::single([
            'value'  => 10.0,
            'unit'   => 'mg',
            'code'   => 'mg',
            'system' => 'http://unitsofmeasure.org',
        ]);
        $right = Collection::single([
            'value'  => 10.0,
            'unit'   => 'mg',
            'code'   => 'mg',
            'system' => 'http://unitsofmeasure.org',
        ]);

        $result = $this->service->compareEquality($left, $right, '=');

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testQuantityEqualityWithConversion(): void
    {
        $left = Collection::single([
            'value'  => 1000.0,
            'unit'   => 'mg',
            'code'   => 'mg',
            'system' => 'http://unitsofmeasure.org',
        ]);
        $right = Collection::single([
            'value'  => 1.0,
            'unit'   => 'g',
            'code'   => 'g',
            'system' => 'http://unitsofmeasure.org',
        ]);

        $result = $this->service->compareEquality($left, $right, '=');

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testQuantityOrderingWithConversion(): void
    {
        $left = Collection::single([
            'value'  => 10.0,
            'unit'   => '[lb_av]',
            'code'   => '[lb_av]',
            'system' => 'http://unitsofmeasure.org',
        ]);
        $right = Collection::single([
            'value'  => 1.0,
            'unit'   => 'kg',
            'code'   => 'kg',
            'system' => 'http://unitsofmeasure.org',
        ]);

        $result = $this->service->compareOrdering($left, $right, fn ($a, $b) => $a > $b);

        self::assertTrue($result->isSingle());
        self::assertTrue($result->first());
    }

    public function testQuantityComparisonMissingCodeReturnsEmpty(): void
    {
        $left = Collection::single([
            'value' => 10.0,
            'unit'  => 'mg',
        ]);
        $right = Collection::single([
            'value'  => 10.0,
            'unit'   => 'mg',
            'code'   => 'mg',
            'system' => 'http://unitsofmeasure.org',
        ]);

        $result = $this->service->compareEquality($left, $right, '=');

        self::assertTrue($result->isEmpty());
    }
}
