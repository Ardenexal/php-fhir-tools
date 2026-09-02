<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\CaseComparison;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonHarness;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\ComparisonReport;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\DeclaredLimitations;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Pins the properties that make the missing-finding total trustworthy, on the real corpus.
 *
 * The unit tests pin the pairing and labelling rules in isolation. These pin the two claims a reader of
 * the number actually relies on: that the capability breakdown accounts for every finding, and that the
 * measurement is finer-grained than the error-count classes it replaces.
 *
 * Not a pin on the totals themselves. Those move whenever a capability lands, and locking them here would
 * turn every genuine improvement into a failing test — the pressure that produced the self-seeded
 * expectations this whole comparison exists to escape.
 */
final class MissingFindingMeasurementTest extends TestCase
{
    /** @var array<string, ComparisonReport> */
    private static array $reports = [];

    /** @return iterable<string, array{FhirVersion}> */
    public static function versions(): iterable
    {
        yield 'R4' => [FhirVersion::R4];
        yield 'R4B' => [FhirVersion::R4B];
        yield 'R5' => [FhirVersion::R5];
    }

    /**
     * The breakdown accounts for every missing finding, so a capability total can be read as work.
     *
     * If the histogram summed to less than the total, findings would be vanishing between measurement and
     * reporting, and every "we closed N" claim measured against it would be wrong by an unknown amount.
     */
    #[DataProvider('versions')]
    public function testTheCapabilityBreakdownSumsToTheMissingTotal(FhirVersion $version): void
    {
        $report = $this->report($version);

        self::assertSame(
            $report->missingFindingCount(),
            array_sum($report->missingFindingHistogram()),
            'capability labels must partition the missing findings',
        );
    }

    /** Per-case contributions account for the total too, so the concentration view can be trusted. */
    #[DataProvider('versions')]
    public function testThePerCaseBreakdownSumsToTheMissingTotal(FhirVersion $version): void
    {
        $report = $this->report($version);

        self::assertSame($report->missingFindingCount(), array_sum($report->missingByCase()));
    }

    /**
     * The measurement is finer-grained than the error-count class, which is the whole point.
     *
     * `Observation-ex-pain` reports two errors from the reference validator and one from us, so the count
     * says two short. Ours restates the first — `This value should not be blank.` on `code` is
     * `Observation.code: minimum required = 1` — so only one finding is genuinely absent. A regression
     * here means the pairing stopped working and the total has silently reinflated.
     */
    public function testTheKnownDifferentlyWordedFindingIsNotCountedAsMissing(): void
    {
        $case = $this->case(FhirVersion::R4, 'Observation-ex-pain.json');

        self::assertSame(2, $case->javaErrorCount, 'corpus drifted: expected two reference errors');
        self::assertSame(1, $case->ourErrorCount, 'our own output drifted');
        self::assertSame(1, $case->delta->count(), 'the differently worded finding must pair, not count');
    }

    /**
     * A document we refuse to read is one finding, however many places the reference validator noticed.
     *
     * `json-no-quotes-2` draws three parse diagnostics against our single rejection. Counting all three
     * would score two checks as missing while we already reject the whole file.
     */
    public function testAnUnreadableDocumentDoesNotCountEachParseDiagnostic(): void
    {
        $case = $this->case(FhirVersion::R4, 'json-no-quotes-2');

        self::assertGreaterThan(1, $case->javaErrorCount, 'corpus drifted: expected several diagnostics');
        self::assertSame(0, $case->delta->count());
    }

    /**
     * Cases rejected before validation are inside the total, not a footnote beside it.
     *
     * They sit in no error-count class by construction, which is exactly why they went unmeasured. R4 and
     * R5 each hold two.
     */
    #[DataProvider('versions')]
    public function testUnreadCasesContributeToTheMissingTotal(FhirVersion $version): void
    {
        $report = $this->report($version);

        foreach ($report->unread as $unreadCase) {
            self::assertSame(
                $unreadCase->javaErrorCount,
                $unreadCase->delta->count(),
                "every reference finding on unread case {$unreadCase->name} is missing by definition",
            );
        }
    }

    /**
     * Every declared limitation is counted, and the count is the one that was reviewed.
     *
     * The property that makes a declared limitation honest. Writing off a finding is a claim, and a claim
     * that cannot fail is worth nothing — this is what stops a new LOINC finding quietly joining the pile,
     * which is exactly how the invariant-keyed suppression this replaced went wrong.
     */
    #[DataProvider('versions')]
    public function testDeclaredLimitationsMatchTheirPinnedCounts(FhirVersion $version): void
    {
        $expected = DeclaredLimitations::EXPECTED_FINDING_COUNTS[$version->value] ?? [];

        self::assertSame(
            $expected,
            $this->report($version)->declaredByReason(),
            'a declared limitation changed size; read why before updating the pin',
        );
    }

    /** Open plus declared is the whole, so neither figure can drift without the other noticing. */
    #[DataProvider('versions')]
    public function testOpenAndDeclaredAccountForEveryMissingFinding(FhirVersion $version): void
    {
        $report = $this->report($version);

        self::assertSame(
            $report->missingFindingCount(),
            $report->openMissingCount() + $report->declaredMissingCount(),
        );
    }

    /**
     * Warning-count divergence is pinned, because it is a decision rather than a defect.
     *
     * ADR-004 makes extensible and preferred binding findings warnings, and the default wiring resolves
     * `FHIRTerminologyClientInterface` to `NullFHIRTerminologyClient` — so offline we emit no warning where
     * the reference validator needed a lookup to emit one. That is by design, and the numbers are pinned so
     * the design cannot drift into a defect unnoticed. They do not gate landing; they gate re-seeding.
     */
    #[DataProvider('versions')]
    public function testWarningDivergenceIsPinned(FhirVersion $version): void
    {
        $expected = ['R4' => 97, 'R4B' => 1, 'R5' => 45];

        self::assertSame(
            $expected[$version->value] ?? null,
            count($this->report($version)->warningMismatches()),
            'warning parity moved; ADR-004 and the terminology wiring decide this, so read why',
        );
    }

    private function case(FhirVersion $version, string $name): CaseComparison
    {
        foreach ($this->report($version)->comparisons as $comparison) {
            if ($comparison->name === $name) {
                return $comparison;
            }
        }

        self::fail("case {$name} is no longer in the compared set for {$version->value}");
    }

    /** One harness run per version, reused across cases; each takes well under a second. */
    private function report(FhirVersion $version): ComparisonReport
    {
        return self::$reports[$version->value] ??= (new ComparisonHarness(
            vendorDir: \dirname(__DIR__, 5) . '/vendor',
            validation: OracleValidationServiceFactory::create($version),
            serialization: FHIRSerializationService::createDefault($version),
            version: $version,
        ))->run();
    }
}
