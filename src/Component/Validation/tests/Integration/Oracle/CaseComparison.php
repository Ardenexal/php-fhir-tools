<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * One case's result: our counts beside Java's, classified.
 *
 * `ourErrorCount` is every error we reported — nothing is filtered out before comparison, which is
 * what makes it apples-to-apples with Java's count. See ComparisonHarness for why no suppression
 * filter belongs anywhere in this comparison.
 */
final class CaseComparison
{
    /**
     * @param list<string> $ourErrorMessages our error messages, for family labelling
     * @param list<string> $families         family labels derived from those messages
     * @param list<string> $javaErrorTexts   Java's error-severity issue texts, so a reviewer can tell
     *                                       "we report something Java does not" from "we report the
     *                                       same finding differently". Counts alone cannot separate
     *                                       those, and M02 must not "fix" a family Java agrees with.
     * @param list<string> $ourErrorPaths    the FHIR path each of our messages was reported on, parallel
     *                                       to $ourErrorMessages. Needed to review a pairing by hand:
     *                                       two validators can word one finding differently, and the
     *                                       element is what shows they mean the same one.
     */
    public function __construct(
        public readonly string $name,
        public readonly int $ourErrorCount,
        public readonly int $ourWarningCount,
        public readonly int $javaErrorCount,
        public readonly int $javaWarningCount,
        public readonly array $ourErrorMessages = [],
        public readonly array $families = [],
        public readonly array $javaErrorTexts = [],
        public readonly array $ourErrorPaths = [],
        /**
         * Reference findings this case leaves unexplained, labelled by capability.
         *
         * Not derivable from `javaErrorCount - ourErrorCount`, and the difference is the point: a case
         * can be `EQUAL` on counts while still missing a finding, because we report a different one Java
         * does not. Arithmetic on the two totals cannot see that; pairing can.
         */
        public readonly FindingDelta $delta = new FindingDelta(),
    ) {
    }

    /**
     * How our error count compares with Java's — on the same basis the specification suite asserts,
     * and on the same basis a caller of the library sees, those now being one and the same thing.
     */
    public function classification(): Classification
    {
        return Classification::compare($this->ourErrorCount, $this->javaErrorCount);
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
