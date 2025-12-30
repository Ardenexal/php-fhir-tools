<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Model;

/**
 * Represents the result of a FHIR profile validation.
 *
 * This class aggregates all validation issues found during validation
 * and provides methods to check validation status and retrieve issues.
 *
 * @author FHIR Tools
 */
class ValidationResult
{
    /**
     * @param array<ValidationIssue> $issues List of validation issues
     */
    public function __construct(
        private array $issues = []
    ) {
    }

    /**
     * Add a validation issue.
     */
    public function addIssue(ValidationIssue $issue): void
    {
        $this->issues[] = $issue;
    }

    /**
     * Add multiple validation issues.
     *
     * @param array<ValidationIssue> $issues
     */
    public function addIssues(array $issues): void
    {
        foreach ($issues as $issue) {
            $this->addIssue($issue);
        }
    }

    /**
     * Check if validation passed (no errors).
     */
    public function isValid(): bool
    {
        return !$this->hasErrors();
    }

    /**
     * Check if there are any error-level issues.
     */
    public function hasErrors(): bool
    {
        foreach ($this->issues as $issue) {
            if ($issue->isError()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if there are any warning-level issues.
     */
    public function hasWarnings(): bool
    {
        foreach ($this->issues as $issue) {
            if ($issue->isWarning()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all validation issues.
     *
     * @return array<ValidationIssue>
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    /**
     * Get only error-level issues.
     *
     * @return array<ValidationIssue>
     */
    public function getErrors(): array
    {
        return array_filter($this->issues, fn (ValidationIssue $issue) => $issue->isError());
    }

    /**
     * Get only warning-level issues.
     *
     * @return array<ValidationIssue>
     */
    public function getWarnings(): array
    {
        return array_filter($this->issues, fn (ValidationIssue $issue) => $issue->isWarning());
    }

    /**
     * Get count of all issues.
     */
    public function getIssueCount(): int
    {
        return count($this->issues);
    }

    /**
     * Get count of error-level issues.
     */
    public function getErrorCount(): int
    {
        return count($this->getErrors());
    }

    /**
     * Get count of warning-level issues.
     */
    public function getWarningCount(): int
    {
        return count($this->getWarnings());
    }

    /**
     * Convert to array representation.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'valid'        => $this->isValid(),
            'issueCount'   => $this->getIssueCount(),
            'errorCount'   => $this->getErrorCount(),
            'warningCount' => $this->getWarningCount(),
            'issues'       => array_map(fn (ValidationIssue $issue) => $issue->toArray(), $this->issues),
        ];
    }

    /**
     * Convert to FHIR OperationOutcome resource.
     *
     * @return array<string, mixed>
     */
    public function toOperationOutcome(): array
    {
        return [
            'resourceType' => 'OperationOutcome',
            'issue'        => array_map(
                fn (ValidationIssue $issue) => $issue->toOperationOutcomeIssue(),
                $this->issues,
            ),
        ];
    }
}
