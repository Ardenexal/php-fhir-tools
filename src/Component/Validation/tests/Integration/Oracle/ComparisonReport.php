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
     * How many reference findings we do not report, across every case including the unread ones.
     *
     * **This is the parity headline, not {@see belowCount()}.** A `BELOW` case count classifies on error
     * totals, which distorts in both directions: it counts a case as short by two when one of the two is
     * our own finding worded differently, and it counts a case as `EQUAL` when both sides report one
     * error and the errors are about different things. Pairing removes both distortions.
     *
     * Unread cases are included because leaving them out is what let them go unmeasured — see
     * {@see UnreadCase}.
     */
    public function missingFindingCount(): int
    {
        $total = 0;
        foreach ($this->comparisons as $comparison) {
            $total += $comparison->delta->count();
        }
        foreach ($this->unread as $unreadCase) {
            $total += $unreadCase->delta->count();
        }

        return $total;
    }

    /**
     * Missing findings nothing in this codebase can close, and how many of each reason.
     *
     * Split out of {@see missingFindingCount()} so the open figure reads as work someone could pick up.
     * A finding lands here only when it names a code system {@see DeclaredLimitations} records as
     * unavailable, and the counts are pinned by test — a new one appearing has to fail a check rather than
     * quietly join the written-off pile, which is how the suppression this replaced went wrong.
     *
     * @return array<string, int> reason => findings blocked, descending
     */
    public function declaredByReason(): array
    {
        $histogram = [];

        foreach ([...$this->comparisons, ...$this->unread] as $case) {
            foreach ($case->delta->reasonHistogram() as $reason => $count) {
                $histogram[$reason] = ($histogram[$reason] ?? 0) + $count;
            }
        }

        // Descending by count, then by reason text, so equal counts have one defined order.
        //
        // `arsort()` alone was not enough. PHP's sort is stable, so reasons tied on count came out in
        // whatever order the corpus happened to produce them — and R5 holds two reasons tied at one.
        // The pin in `MissingFindingMeasurementTest` compares with `assertSame`, which is order
        // sensitive, so adding or removing an unrelated case could flip a tied pair and fail it with
        // "a declared limitation changed size" when nothing had changed size. Sorting the tiebreak
        // explicitly keeps the pin strict about counts without making it hostage to iteration order.
        $counts = $histogram;
        uksort(
            $histogram,
            static fn (string $first, string $second): int => [$counts[$second], $first] <=> [$counts[$first], $second],
        );

        return $histogram;
    }

    /** Missing findings blocked by something outside this codebase. */
    public function declaredMissingCount(): int
    {
        return array_sum($this->declaredByReason());
    }

    /**
     * Missing findings someone could actually close.
     *
     * The figure the capability milestones are sized against. {@see missingFindingCount()} stays the
     * arithmetic total so the two can be reconciled: open + declared is always the whole.
     */
    public function openMissingCount(): int
    {
        return $this->missingFindingCount() - $this->declaredMissingCount();
    }

    /**
     * Case name => how many findings it is missing, largest first.
     *
     * The total alone is a misleading way to size work, because the distribution is extremely uneven:
     * on R4, `japanese-utf8-ok` contributes 108 findings on its own, all downstream of one refusal to
     * read a file whose encoding the reference validator tolerates. Fixing that reads as a hundred-plus
     * improvement while closing one capability. Anyone choosing what to work on needs to see the
     * concentration, not just the sum.
     *
     * @return array<string, int> descending by count, cases missing nothing omitted
     */
    public function missingByCase(): array
    {
        $byCase = [];

        foreach ([...$this->comparisons, ...$this->unread] as $case) {
            $count = $case->delta->count();
            if ($count > 0) {
                $byCase[$case->name] = $count;
            }
        }

        arsort($byCase);

        return $byCase;
    }

    /**
     * Capability label => how many missing findings need it, across every case including unread ones.
     *
     * The totals sum to {@see missingFindingCount()} by construction, because
     * {@see MissingFindingClassifier} labels every finding and falls back to `other` rather than
     * dropping one. A partition that did not sum would mean findings were being lost in the measurement.
     *
     * @return array<string, int> descending by count
     */
    public function missingFindingHistogram(): array
    {
        $histogram = [];

        foreach ([...$this->comparisons, ...$this->unread] as $case) {
            foreach ($case->delta->labelHistogram() as $label => $count) {
                $histogram[$label] = ($histogram[$label] ?? 0) + $count;
            }
        }

        arsort($histogram);

        return $histogram;
    }

    /**
     * Compared cases missing at least one finding of this capability, largest contribution first.
     *
     * The review list behind a label: `--family=<label>` prints these with both sides' findings so a
     * pairing can be judged by eye. Unread cases are excluded because there is no side of ours to show.
     *
     * @return list<CaseComparison>
     */
    public function casesNeeding(string $label): array
    {
        $matching = array_values(array_filter(
            $this->comparisons,
            static fn (CaseComparison $c): bool => $c->delta->needs($label),
        ));

        usort(
            $matching,
            static fn (CaseComparison $a, CaseComparison $b): int => count($b->delta->findingsFor($label)) <=> count($a->delta->findingsFor($label)),
        );

        return $matching;
    }

    /**
     * Unread cases missing at least one finding of this capability.
     *
     * Kept separate from {@see casesNeeding()} rather than merged into it: these carry no findings of
     * ours, so a caller printing a side-by-side has nothing to put in the left column and needs to know
     * that rather than render an empty one.
     *
     * @return list<UnreadCase>
     */
    public function unreadNeeding(string $label): array
    {
        return array_values(array_filter(
            $this->unread,
            static fn (UnreadCase $c): bool => $c->delta->needs($label),
        ));
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
