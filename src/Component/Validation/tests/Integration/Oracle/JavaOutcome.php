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
    /**
     * Stands in for a counted error whose text the corpus does not carry.
     *
     * Some manifest entries state counts inline instead of naming an outcome file — `json-prop-key` is
     * one — so there is a count with no message behind it. Such a finding still has to be counted and
     * labelled, or the missing-finding total quietly shrinks by however many texts happened to be absent,
     * which is the same class of silent loss the skip histogram exists to prevent.
     */
    public const TEXT_UNAVAILABLE = '(the corpus records this error as a count with no message)';

    /** @var list<string> error/fatal issue texts, one per counted error; see {@see TEXT_UNAVAILABLE} */
    public readonly array $errorTexts;

    /**
     * @var list<string> where in the instance each error was found, one per counted error, `''` when the
     *                   outcome does not say. Parallel to {@see $errorTexts}
     */
    public readonly array $errorExpressions;

    /**
     * @param list<string> $errorTexts       issue detail texts at error/fatal severity, for family labelling
     * @param list<string> $errorExpressions The issue's `expression` or `location`, which is the only thing
     *                                       in the outcome that says *which* element a finding is about. The
     *                                       message names the element by type — `List.status` — so without
     *                                       this a document containing two Lists cannot be told apart.
     */
    public function __construct(
        public readonly int $errorCount,
        public readonly int $warningCount,
        public readonly int $infoCount,
        array $errorTexts = [],
        array $errorExpressions = [],
    ) {
        // Invariant: one text and one expression per counted error, so `count(...) === $errorCount`
        // everywhere downstream. Padding rather than trusting the caller keeps the guarantee in one place.
        while (count($errorTexts) < $errorCount) {
            $errorTexts[] = self::TEXT_UNAVAILABLE;
        }

        while (count($errorExpressions) < $errorCount) {
            $errorExpressions[] = '';
        }

        $this->errorTexts       = array_values($errorTexts);
        $this->errorExpressions = array_values($errorExpressions);
    }

    public function totalIssueCount(): int
    {
        return $this->errorCount + $this->warningCount + $this->infoCount;
    }
}
