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
     * @param list<UnreadCase>          $unread      cases the deserializer rejected, carrying the Java
     *                                               outcome they were never compared against
     */
    public function __construct(
        public readonly array $comparisons,
        public readonly float $wallClockSeconds = 0.0,
        public readonly array $skips = [],
        public readonly array $unread = [],
    ) {
    }

    public function unreadCount(): int
    {
        return count($this->unread);
    }

    /**
     * How many Java error reports these unread cases are hiding.
     *
     * This is the number the skip histogram cannot express. "deserialize-threw=17" says seventeen
     * cases are missing; it does not say whether that is seventeen trivial parse rejections or a
     * hundred-plus reference findings going unmeasured. Deciding what to fix next needs the latter.
     */
    public function unreadJavaErrorCount(): int
    {
        return array_sum(array_map(static fn (UnreadCase $c): int => $c->javaErrorCount, $this->unread));
    }

    /**
     * Unread cases ordered by how much reference behaviour each one hides, largest first.
     *
     * @return list<UnreadCase>
     */
    public function unreadByImpact(): array
    {
        $sorted = $this->unread;
        usort($sorted, static fn (UnreadCase $a, UnreadCase $b): int => $b->javaErrorCount <=> $a->javaErrorCount);

        return $sorted;
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
     * Cases where our warning count differs from Java's.
     *
     * Warnings never gate landing the cascade — they do not affect validity. But the specification
     * suite asserts them, so re-seeding while these disagree writes an unreviewed count in as
     * correct. This is the list a reviewer must read before the re-seeding gate.
     *
     * @return list<CaseComparison>
     */
    public function warningMismatches(): array
    {
        return array_values(
            array_filter($this->comparisons, static fn (CaseComparison $c): bool => !$c->warningsAgree()),
        );
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
