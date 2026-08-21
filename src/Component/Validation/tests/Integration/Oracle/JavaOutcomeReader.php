<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle;

/**
 * Reads the Java reference-validator outcome for a manifest case.
 *
 * A manifest case's "java" key is either an inline object ({"errorCount": N, ...}) or a path to an
 * OperationOutcome JSON file relative to the validator outcomes directory. Cases with no "java"
 * key have no oracle and must be excluded from comparison rather than treated as zero.
 */
final class JavaOutcomeReader
{
    public function __construct(
        private readonly string $validatorBaseDir,
    ) {
    }

    /**
     * @param array<string, mixed> $case a single manifest test-case entry
     *
     * @return JavaOutcome|null null when the case declares no Java outcome, or the file is missing
     *                          or unreadable — meaning there is no oracle, not that there are no issues
     */
    public function read(array $case): ?JavaOutcome
    {
        $java = $case['java'] ?? null;

        if ($java === null) {
            return null;
        }

        // Inline object: the manifest states the counts directly and carries no issue texts.
        if (is_array($java)) {
            return new JavaOutcome(
                errorCount: (int) ($java['errorCount'] ?? 0),
                warningCount: (int) ($java['warningCount'] ?? 0),
                infoCount: (int) ($java['infoCount'] ?? 0),
            );
        }

        if (!is_string($java)) {
            return null;
        }

        $outcomePath = $this->validatorBaseDir . '/outcomes/' . $java;
        if (!file_exists($outcomePath)) {
            return null;
        }

        $raw = file_get_contents($outcomePath);
        if ($raw === false) {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return null;
        }

        return self::fromOperationOutcome($decoded);
    }

    /**
     * Parse counts out of a decoded OperationOutcome.
     *
     * An OperationOutcome with no "issue" array means the validator found nothing — zero of
     * everything, which is a real result rather than a missing oracle.
     *
     * @param array<string, mixed> $outcome
     */
    public static function fromOperationOutcome(array $outcome): JavaOutcome
    {
        $issues = $outcome['issue'] ?? null;
        if (!is_array($issues)) {
            return new JavaOutcome(0, 0, 0);
        }

        $errors           = 0;
        $warnings         = 0;
        $info             = 0;
        $errorTexts       = [];
        $errorExpressions = [];

        foreach ($issues as $issue) {
            if (!is_array($issue)) {
                continue;
            }

            $severity = $issue['severity'] ?? '';

            match (true) {
                in_array($severity, ['error', 'fatal'], true) => $errors++,
                $severity === 'warning'                       => $warnings++,
                $severity === 'information'                   => $info++,
                default                                       => null,
            };

            if (in_array($severity, ['error', 'fatal'], true)) {
                // Both lists grow exactly once per error, even when the issue carries neither field. They
                // are read in parallel, so appending only when a value exists would silently shift every
                // later expression onto the wrong text.
                $details      = $issue['details'] ?? null;
                $errorTexts[] = is_array($details) && is_string($details['text'] ?? null)
                    ? $details['text']
                    : JavaOutcome::TEXT_UNAVAILABLE;

                // `expression` is FHIRPath into the instance; `location` is the older XPath-ish form of the
                // same thing. Either says which element the finding is about, which the message text does
                // not: it names the element by type (`List.status`), so two Lists in one document are
                // indistinguishable without this.
                $where              = $issue['expression'] ?? $issue['location'] ?? null;
                $errorExpressions[] = is_array($where) && is_string($where[0] ?? null) ? $where[0] : '';
            }
        }

        return new JavaOutcome($errors, $warnings, $info, $errorTexts, $errorExpressions);
    }
}
