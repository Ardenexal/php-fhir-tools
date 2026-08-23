<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * A case the deserializer rejected, paired with the Java outcome we therefore never compared against.
 *
 * These are invisible to ABOVE/EQUAL/BELOW by construction: {@see ComparisonHarness::compareCase()}
 * returns before a {@see CaseComparison} exists, so the case is in no class at all. The skip histogram
 * counts them, but a count alone only ever reveals *movement* — it cannot say how much reference
 * behaviour is going unmeasured, which is the number that matters when deciding what to work on next.
 *
 * Recording Java's counts here is possible only because the harness reads the oracle *before* it
 * attempts deserialization. So an unread case still knows exactly what it failed to find.
 *
 * Deliberately NOT a fourth {@see Classification}: that enum is produced by comparing two integers, and
 * an unread case has no integer of ours to compare. Making it a class would force every existing
 * ABOVE/EQUAL/BELOW roll-up to special-case a value that can never come out of
 * {@see Classification::compare()}.
 */
final class UnreadCase
{
    public function __construct(
        public readonly string $name,
        public readonly int $javaErrorCount,
        public readonly int $javaWarningCount,
        /** Why deserialization failed, as reported to a caller — the diagnostic, not just the fact. */
        public readonly string $failureMessage,
    ) {
    }
}
