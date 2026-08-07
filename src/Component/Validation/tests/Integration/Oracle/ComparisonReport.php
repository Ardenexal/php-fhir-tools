<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * Aggregate of every compared case, with the roll-ups the milestones report against.
 *
 * The headline number is aboveCount(): cases where we report more errors than the Java reference
 * validator. M02 cannot land until it is zero.
 */
final class ComparisonReport
{
    /**
     * @param list<CaseComparison>      $comparisons
     * @param array<string, SkipReason> $skips       case name => why it was not compared
     */
    public function __construct(
        public readonly array $comparisons,
        public readonly float $wallClockSeconds = 0.0,
        public readonly array $skips = [],
    ) {
    }

    public function skippedCount(): int
    {
        return count($this->skips);
    }

    /**
     * Skip counts per reason, so a post-change run can be checked against the baseline
     * arithmetically. A case that starts crashing leaves the comparison set and lands here, which
     * would otherwise read as one fewer ABOVE case — an apparent improvement.
     *
     * @return array<string, int> keyed by SkipReason value, including reasons with a zero count
     */
    public function skipHistogram(): array
    {
        $histogram = [];
        foreach (SkipReason::cases() as $reason) {
            $histogram[$reason->value] = 0;
        }

        foreach ($this->skips as $reason) {
            ++$histogram[$reason->value];
        }

        return $histogram;
    }

    /**
     * Cases dropped because validation itself threw. This must be empty; anything here is a crash
     * being silently scored as "not a false positive".
     *
     * @return list<string>
     */
    public function crashedCases(): array
    {
        return array_keys(
            array_filter($this->skips, static fn (SkipReason $r): bool => $r === SkipReason::ValidateCrashed),
        );
    }

    /** @return list<CaseComparison> */
    public function byClassification(Classification $classification): array
    {
        return array_values(
            array_filter(
                $this->comparisons,
                static fn (CaseComparison $c): bool => $c->classification() === $classification,
            ),
        );
    }

    public function aboveCount(): int
    {
        return count($this->byClassification(Classification::Above));
    }

    public function equalCount(): int
    {
        return count($this->byClassification(Classification::Equal));
    }

    public function belowCount(): int
    {
        return count($this->byClassification(Classification::Below));
    }

    /**
     * Cases whose class changes depending on whether isKnownGap() suppression is applied.
     *
     * A non-empty result means the filtered comparison is not apples-to-apples with Java, because
     * Java's counts are never filtered. This is the evidence for M00's severity-mapping task.
     *
     * @return list<CaseComparison>
     */
    public function skewedCases(): array
    {
        return array_values(
            array_filter($this->comparisons, static fn (CaseComparison $c): bool => $c->isSkewedByKnownGapFilter()),
        );
    }

    /**
     * Family label => number of error violations carrying it, across every ABOVE case.
     *
     * Restricted to ABOVE because those are the false positives worth fixing; counting families
     * over EQUAL cases would inflate families that Java agrees with.
     *
     * @return array<string, int> sorted by count, descending
     */
    public function aboveFamilyHistogram(): array
    {
        $histogram = [];

        foreach ($this->byClassification(Classification::Above) as $comparison) {
            foreach ($comparison->families as $family) {
                $histogram[$family] = ($histogram[$family] ?? 0) + 1;
            }
        }

        arsort($histogram);

        return $histogram;
    }
}
