<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Oracle;

use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CaseComparison;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonReport;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\FindingDelta;
use PHPUnit\Framework\TestCase;

/**
 * The declared-limitation histogram, whose order a strict pin depends on.
 *
 * @author Ardenexal
 */
class ComparisonReportTest extends TestCase
{
    /**
     * Reasons tied on count are ordered by reason text, not by the order the corpus produced them.
     *
     * `MissingFindingMeasurementTest::testDeclaredLimitationsMatchTheirPinnedCounts()` compares this
     * histogram with `assertSame`, which is order sensitive, and R5's real pin holds two reasons tied at
     * one. Sorting on count alone left those two in whatever order the cases happened to arrive, so
     * adding or removing an unrelated corpus case could flip them and fail the pin with "a declared
     * limitation changed size" when no size had changed. Same reasons, opposite arrival order, one
     * expected result.
     */
    public function testTiedReasonsAreOrderedByTextRatherThanByArrival(): void
    {
        $forward  = self::report(['zebra reason', 'alpha reason']);
        $backward = self::report(['alpha reason', 'zebra reason']);

        $expected = ['alpha reason' => 1, 'zebra reason' => 1];

        self::assertSame($expected, $forward->declaredByReason());
        self::assertSame($expected, $backward->declaredByReason(), 'arrival order must not decide a tie');
    }

    /** Count still dominates: a larger reason leads regardless of where its text sorts. */
    public function testHigherCountsStillComeFirst(): void
    {
        $report = self::report(['zebra reason', 'zebra reason', 'alpha reason']);

        self::assertSame(['zebra reason' => 2, 'alpha reason' => 1], $report->declaredByReason());
    }

    /**
     * A report holding one case per reason occurrence, in the order given.
     *
     * @param list<string> $reasons one declared finding per entry, labelled with that reason
     */
    private static function report(array $reasons): ComparisonReport
    {
        $comparisons = [];

        foreach ($reasons as $index => $reason) {
            $comparisons[] = new CaseComparison(
                name: 'case-' . $index,
                ourErrorCount: 0,
                ourWarningCount: 0,
                javaErrorCount: 1,
                javaWarningCount: 0,
                delta: new FindingDelta(
                    findings: ['a reference finding'],
                    labels: ['terminology:code'],
                    reasons: [$reason],
                ),
            );
        }

        return new ComparisonReport($comparisons);
    }
}
