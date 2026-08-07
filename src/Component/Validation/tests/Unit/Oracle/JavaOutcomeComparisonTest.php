<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Oracle;

use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CaseComparison;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\Classification;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonReport;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\JavaOutcomeReader;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\SkipReason;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ViolationFamilyClassifier;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Tests the conformance-comparison harness itself.
 *
 * The harness is the instrument the nested-cascade plan reports against, so a miscount here would
 * silently invalidate every ABOVE/EQUAL/BELOW number the milestones cite. The two real OperationOutcome
 * shapes below are lifted from the vendored corpus (containedToContainer and hakan-se), which are the
 * plan's discriminating pair.
 */
#[CoversClass(Classification::class)]
#[CoversClass(JavaOutcomeReader::class)]
#[CoversClass(CaseComparison::class)]
#[CoversClass(ComparisonReport::class)]
#[CoversClass(ViolationFamilyClassifier::class)]
#[CoversClass(SkipReason::class)]
final class JavaOutcomeComparisonTest extends TestCase
{
    #[DataProvider('provideClassifications')]
    public function testClassificationComparesErrorCounts(int $ours, int $java, Classification $expected): void
    {
        self::assertSame($expected, Classification::compare($ours, $java));
    }

    /** @return iterable<string, array{int, int, Classification}> */
    public static function provideClassifications(): iterable
    {
        yield 'we report more than Java'      => [2, 0, Classification::Above];
        yield 'we agree with Java'            => [4, 4, Classification::Equal];
        yield 'we report fewer than Java'     => [2, 4, Classification::Below];
        yield 'both clean'                    => [0, 0, Classification::Equal];
    }

    /**
     * An OperationOutcome with no issue array is a real "nothing found" result, not a missing oracle.
     * This is the containedToContainer shape, which Java passes cleanly and we regress on.
     */
    public function testOutcomeWithoutIssuesIsZeroOfEverything(): void
    {
        $outcome = JavaOutcomeReader::fromOperationOutcome(['resourceType' => 'OperationOutcome']);

        self::assertSame(0, $outcome->errorCount);
        self::assertSame(0, $outcome->warningCount);
        self::assertSame(0, $outcome->infoCount);
    }

    /**
     * The hakan-se shape. This case is why the severity mapping needed validating: it carries six
     * issues but only four errors, and the 2026-08-07 measurement compared our error count against
     * the total issue count.
     */
    public function testSeveritiesAreCountedIntoSeparateTiers(): void
    {
        $outcome = JavaOutcomeReader::fromOperationOutcome([
            'resourceType' => 'OperationOutcome',
            'issue'        => [
                ['severity' => 'error', 'details' => ['text' => 'minimum required = 1, but only found 0']],
                ['severity' => 'information', 'details' => ['text' => 'A definition could not be found']],
                ['severity' => 'error', 'details' => ['text' => 'If a date has a time, it must have a timezone']],
                ['severity' => 'error', 'details' => ['text' => 'Unable to resolve resource with reference']],
                ['severity' => 'error', 'details' => ['text' => 'Constraint failed: ref-1']],
                ['severity' => 'warning', 'details' => ['text' => 'Profile reference has not been checked']],
            ],
        ]);

        self::assertSame(4, $outcome->errorCount, 'four issues are error severity');
        self::assertSame(1, $outcome->warningCount);
        self::assertSame(1, $outcome->infoCount);
        self::assertSame(6, $outcome->totalIssueCount());
        self::assertCount(4, $outcome->errorTexts);
    }

    /** FHIR "fatal" has no separate tier in our report, so it is counted as an error on both sides. */
    public function testFatalCountsAsError(): void
    {
        $outcome = JavaOutcomeReader::fromOperationOutcome([
            'issue' => [
                ['severity' => 'fatal', 'details' => ['text' => 'unparseable']],
                ['severity' => 'error', 'details' => ['text' => 'bad']],
            ],
        ]);

        self::assertSame(2, $outcome->errorCount);
    }

    public function testInlineJavaObjectIsReadWithoutAnOutcomeFile(): void
    {
        $reader  = new JavaOutcomeReader('/nonexistent');
        $outcome = $reader->read(['java' => ['errorCount' => 3, 'warningCount' => 1]]);

        self::assertNotNull($outcome);
        self::assertSame(3, $outcome->errorCount);
        self::assertSame(1, $outcome->warningCount);
    }

    /** A case with no Java key has no oracle. Null must not be conflated with zero errors. */
    public function testMissingJavaKeyYieldsNoOracle(): void
    {
        $reader = new JavaOutcomeReader('/nonexistent');

        self::assertNull($reader->read(['name' => 'some-case']));
        self::assertNull($reader->read(['java' => 'missing-file.json']));
    }

    public function testKnownGapSuppressionIsReportedAsSkew(): void
    {
        // Unfiltered we report 2 errors and Java reports 1, but isKnownGap() hides one of ours,
        // making the filtered comparison look like agreement. That divergence must be visible.
        $comparison = new CaseComparison(
            name: 'skewed-case',
            ourErrorCount: 1,
            ourErrorCountUnfiltered: 2,
            ourWarningCount: 0,
            javaErrorCount: 1,
            javaWarningCount: 0,
        );

        self::assertSame(Classification::Equal, $comparison->classification());
        self::assertSame(Classification::Above, $comparison->unfilteredClassification());
        self::assertTrue($comparison->isSkewedByKnownGapFilter());
        self::assertSame(1, $comparison->suppressedByKnownGap());
    }

    public function testReportRollsUpCountsAndAboveFamilies(): void
    {
        $report = new ComparisonReport([
            self::comparison('a', ours: 2, java: 0, families: ['invariant:ref-1', 'invariant:ref-1']),
            self::comparison('b', ours: 1, java: 0, families: ['invariant:per-1']),
            self::comparison('c', ours: 4, java: 4, families: ['invariant:ele-1']),
            self::comparison('d', ours: 0, java: 3, families: []),
        ]);

        self::assertSame(2, $report->aboveCount());
        self::assertSame(1, $report->equalCount());
        self::assertSame(1, $report->belowCount());

        // Families are counted over ABOVE cases only — 'c' is EQUAL, so ele-1 must not appear.
        self::assertSame(
            ['invariant:ref-1' => 2, 'invariant:per-1' => 1],
            $report->aboveFamilyHistogram(),
        );
    }

    /**
     * A crash removes a case from the comparison set, which lowers ABOVE and looks like a fix.
     * The report must keep those attributable rather than folding them into one skip total.
     */
    public function testSkipsAreCategorisedAndCrashesSurfaced(): void
    {
        $report = new ComparisonReport(
            comparisons: [self::comparison('ok', ours: 1, java: 1, families: [])],
            skips: [
                'no-java-key'   => SkipReason::NoOracle,
                'bad-extension' => SkipReason::Unreadable,
                'blew-up'       => SkipReason::ValidateCrashed,
                'also-blew-up'  => SkipReason::ValidateCrashed,
            ],
        );

        self::assertSame(4, $report->skippedCount());
        self::assertSame(
            [
                'no-oracle'         => 1,
                'unreadable'        => 1,
                'deserialize-threw' => 0,
                'validate-crashed'  => 2,
            ],
            $report->skipHistogram(),
        );
        self::assertSame(['blew-up', 'also-blew-up'], $report->crashedCases());
    }

    public function testCleanRunReportsNoCrashes(): void
    {
        $report = new ComparisonReport([self::comparison('ok', ours: 0, java: 0, families: [])]);

        self::assertSame(0, $report->skippedCount());
        self::assertSame([], $report->crashedCases());
        self::assertSame(0, $report->skipHistogram()['validate-crashed']);
    }

    /**
     * Warnings are classified separately and must never gate landing — they do not affect validity.
     * They do gate re-seeding, because seed-outcomes.php writes warning counts into the expectations.
     */
    public function testWarningsAreClassifiedIndependentlyOfErrors(): void
    {
        $agreesOnErrorsNotWarnings = new CaseComparison(
            name: 'noisy',
            ourErrorCount: 1,
            ourErrorCountUnfiltered: 1,
            ourWarningCount: 16,
            javaErrorCount: 1,
            javaWarningCount: 0,
        );

        self::assertSame(Classification::Equal, $agreesOnErrorsNotWarnings->classification());
        self::assertSame(Classification::Above, $agreesOnErrorsNotWarnings->warningClassification());
        self::assertFalse($agreesOnErrorsNotWarnings->warningsAgree());

        $report = new ComparisonReport([
            $agreesOnErrorsNotWarnings,
            self::comparison('quiet', ours: 0, java: 0, families: []),
        ]);

        // Errors agree everywhere, so nothing blocks landing...
        self::assertSame(0, $report->aboveCount());
        // ...but the warning disagreement must still be visible to a reviewer.
        self::assertCount(1, $report->warningMismatches());
        self::assertSame('noisy', $report->warningMismatches()[0]->name);
    }

    public function testFamilyClassifierPrefersInvariantKey(): void
    {
        $classifier = new ViolationFamilyClassifier();

        self::assertSame('invariant:ref-1', $classifier->classify(self::violation(invariantKey: 'ref-1')));
        self::assertSame(
            'constraint:NotBlank',
            $classifier->classify(self::violation(constraintClass: NotBlank::class)),
        );
    }

    /** @param list<string> $families */
    private static function comparison(string $name, int $ours, int $java, array $families): CaseComparison
    {
        return new CaseComparison(
            name: $name,
            ourErrorCount: $ours,
            ourErrorCountUnfiltered: $ours,
            ourWarningCount: 0,
            javaErrorCount: $java,
            javaWarningCount: 0,
            families: $families,
        );
    }

    private static function violation(
        ?string $invariantKey = null,
        string $constraintClass = 'Some\\Constraint',
        string $message = 'boom',
    ): FHIRValidationViolation {
        return new FHIRValidationViolation(
            severity: 'error',
            path: 'Patient.name',
            message: $message,
            constraintClass: $constraintClass,
            profileGroup: null,
            invariantKey: $invariantKey,
        );
    }
}
