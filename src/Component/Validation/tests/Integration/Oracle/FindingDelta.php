<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * The reference findings one case leaves unexplained, each labelled with the capability it needs.
 *
 * Produced by pairing both sides' findings ({@see JavaFindingMatcher}) and labelling the leftovers
 * ({@see MissingFindingClassifier}). This is the quantity the parity work is sized against, in place of
 * the `BELOW` case count — that count classifies on error *totals*, so it both overstates cases where we
 * word a finding differently and misses cases that agree on the total while reporting different things.
 *
 * Shared by {@see CaseComparison} and {@see UnreadCase} because both need the same pair of parallel
 * lists. An unread case simply has no findings of ours, so every reference finding is missing.
 */
final class FindingDelta
{
    /**
     * @param list<string>                                                           $findings reference findings with no counterpart in ours, in original order
     * @param list<string>                                                           $labels   the capability each finding needs, parallel to $findings
     * @param list<?string>                                                          $reasons  why each finding
     *                                                                                         cannot be decided offline, `null` when nothing blocks it. Parallel to $findings. A finding with
     *                                                                                         a reason is a declared limitation rather than open work, and is reported apart from the open
     *                                                                                         total so the backlog reads as work someone can actually do.
     * @param list<array{java: string, ours: string, ourPath: string, rule: string}> $matched  the pairings that
     *                                                                                         removed a finding from $findings, each naming the rule that made it. Carried here rather than
     *                                                                                         re-derived by a caller, because an audit that re-runs the matcher checks a second computation
     *                                                                                         instead of the one the count came from — and a false pair is invisible in $findings.
     */
    public function __construct(
        public readonly array $findings = [],
        public readonly array $labels = [],
        public readonly array $reasons = [],
        public readonly array $matched = [],
    ) {
    }

    /** Findings that are nobody's work, because something outside this codebase blocks them. */
    public function declaredCount(): int
    {
        return count(array_filter($this->reasons, static fn (?string $r): bool => $r !== null));
    }

    /** Findings someone could actually close. */
    public function openCount(): int
    {
        return $this->count() - $this->declaredCount();
    }

    /**
     * Reason => how many findings it blocks here.
     *
     * @return array<string, int>
     */
    public function reasonHistogram(): array
    {
        $histogram = [];
        foreach ($this->reasons as $reason) {
            if ($reason !== null) {
                $histogram[$reason] = ($histogram[$reason] ?? 0) + 1;
            }
        }

        arsort($histogram);

        return $histogram;
    }

    /**
     * Label every reference finding, on the understanding that none of them is matched.
     *
     * The constructor for an unread case, where we produced nothing to pair against.
     *
     * @param list<string> $javaErrorTexts
     */
    public static function allMissing(array $javaErrorTexts, MissingFindingClassifier $classifier): self
    {
        return new self(
            $javaErrorTexts,
            $classifier->classifyAll($javaErrorTexts),
            array_map(static fn (string $t): ?string => DeclaredLimitations::reasonFor($t), $javaErrorTexts),
        );
    }

    /** How many reference findings go unexplained here. */
    public function count(): int
    {
        return count($this->findings);
    }

    /**
     * Capability label => how many missing findings need it.
     *
     * @return array<string, int> descending by count
     */
    public function labelHistogram(): array
    {
        $histogram = [];
        foreach ($this->labels as $label) {
            $histogram[$label] = ($histogram[$label] ?? 0) + 1;
        }

        arsort($histogram);

        return $histogram;
    }

    /** Whether any missing finding needs this capability. */
    public function needs(string $label): bool
    {
        return in_array($label, $this->labels, true);
    }

    /**
     * The missing findings needing one capability.
     *
     * @return list<string>
     */
    public function findingsFor(string $label): array
    {
        $matching = [];
        foreach ($this->findings as $index => $finding) {
            if (($this->labels[$index] ?? null) === $label) {
                $matching[] = $finding;
            }
        }

        return $matching;
    }
}
