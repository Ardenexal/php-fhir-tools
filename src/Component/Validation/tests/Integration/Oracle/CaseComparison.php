<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * One case's result: our counts beside Java's, classified.
 *
 * Both a filtered and a raw error count are carried deliberately. FHIRValidatorSpecificationTest
 * asserts against counts with isKnownGap() applied, but the Java side is never filtered, so
 * comparing filtered-ours against unfiltered-Java understates us wherever a known gap is
 * suppressed. Keeping both makes that skew measurable instead of invisible — see
 * ComparisonReport::skewedCases().
 */
final class CaseComparison
{
    /**
     * @param list<string> $ourErrorMessages filtered error messages, for family labelling
     * @param list<string> $families         family labels derived from those messages
     * @param list<string> $javaErrorTexts   Java's error-severity issue texts, so a reviewer can tell
     *                                       "we report something Java does not" from "we report the
     *                                       same finding differently". Counts alone cannot separate
     *                                       those, and M02 must not "fix" a family Java agrees with.
     */
    public function __construct(
        public readonly string $name,
        public readonly int $ourErrorCount,
        public readonly int $ourErrorCountUnfiltered,
        public readonly int $ourWarningCount,
        public readonly int $javaErrorCount,
        public readonly int $javaWarningCount,
        public readonly array $ourErrorMessages = [],
        public readonly array $families = [],
        public readonly array $javaErrorTexts = [],
    ) {
    }

    /** Classification on the same basis the specification suite asserts (known gaps suppressed). */
    public function classification(): Classification
    {
        return Classification::compare($this->ourErrorCount, $this->javaErrorCount);
    }

    /** Classification with nothing suppressed — what a caller of the library actually sees. */
    public function unfilteredClassification(): Classification
    {
        return Classification::compare($this->ourErrorCountUnfiltered, $this->javaErrorCount);
    }

    /** True when isKnownGap() suppression is what moves this case between classes. */
    public function isSkewedByKnownGapFilter(): bool
    {
        return $this->classification() !== $this->unfilteredClassification();
    }

    public function suppressedByKnownGap(): int
    {
        return $this->ourErrorCountUnfiltered - $this->ourErrorCount;
    }

    /**
     * How our warning count compares with Java's.
     *
     * Tracked separately from {@see classification()} because warnings do not affect validity and
     * must never gate landing the cascade — but they are still asserted by the specification suite,
     * and re-seeding while they disagree bakes an unreviewed count in as correct. An error-only
     * comparison reporting "zero ABOVE" does not mean "agrees with Java".
     */
    public function warningClassification(): Classification
    {
        return Classification::compare($this->ourWarningCount, $this->javaWarningCount);
    }

    public function warningsAgree(): bool
    {
        return $this->warningClassification() === Classification::Equal;
    }
}
