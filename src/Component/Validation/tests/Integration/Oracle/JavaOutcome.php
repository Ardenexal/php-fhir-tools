<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * Counts parsed from a single HL7 Java reference-validator OperationOutcome.
 *
 * The Java corpus is the only conformance oracle available offline. The ardenexal outcome files
 * under tests/Integration/outcomes/ardenexal/ are seeded from our own validator and are a
 * regression lock, not an oracle — never compare against those to decide correctness.
 *
 * FHIR issue severities are fatal | error | warning | information. Our own report collapses
 * fatal into error (FHIRValidationReport has no fatal tier), so fatal is counted as an error here
 * to keep both sides on the same scale.
 */
final class JavaOutcome
{
    /** @param list<string> $errorTexts Issue detail texts at error/fatal severity, for family labelling. */
    public function __construct(
        public readonly int $errorCount,
        public readonly int $warningCount,
        public readonly int $infoCount,
        public readonly array $errorTexts = [],
    ) {
    }

    public function totalIssueCount(): int
    {
        return $this->errorCount + $this->warningCount + $this->infoCount;
    }
}
