<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueSeverityType as R4IssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueTypeType as R4IssueTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcome\OperationOutcomeIssue as R4Issue;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcomeResource as R4Outcome;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\IssueSeverityType as R4BIssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\IssueTypeType as R4BIssueTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\OperationOutcome\OperationOutcomeIssue as R4BIssue;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\OperationOutcomeResource as R4BOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\IssueSeverityType as R5IssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\IssueTypeType as R5IssueTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcome\OperationOutcomeIssue as R5Issue;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\OperationOutcomeResource as R5Outcome;

/**
 * Converts a FHIRValidationReport into a version-appropriate OperationOutcomeResource.
 *
 * The generated IssueType enum only contains top-level group codes. Sub-codes such as
 * 'invariant' and 'value' are not enum cases but are valid codes in the FHIR issue-type
 * value set; they are passed as raw strings via CodePrimitive which accepts any string.
 */
final class FHIRValidationReportMapper
{
    /**
     * @param string $fhirVersion 'R4' | 'R4B' | 'R5'
     *
     * @return object OperationOutcomeResource for the requested FHIR version
     */
    public function toOperationOutcome(
        FHIRValidationReport $report,
        string $fhirVersion = 'R4',
    ): object {
        return match ($fhirVersion) {
            'R4'    => $this->buildR4($report->violations),
            'R4B'   => $this->buildR4B($report->violations),
            'R5'    => $this->buildR5($report->violations),
            default => throw new \InvalidArgumentException(sprintf('Unsupported FHIR version "%s". Supported values: R4, R4B, R5.', $fhirVersion)),
        };
    }

    /** @param list<FHIRValidationViolation> $violations */
    private function buildR4(array $violations): R4Outcome
    {
        return new R4Outcome(
            issue: $violations !== []
            ? array_map(fn (FHIRValidationViolation $v): R4Issue => new R4Issue(
                severity: new R4IssueSeverityType($this->mapSeverity($v->severity)),
                code: new R4IssueTypeType($this->mapIssueType($v)),
                diagnostics: $v->message,
                expression: $v->path !== '' ? [$v->path] : [],
            ), $violations)
            : [new R4Issue(
                severity: new R4IssueSeverityType('information'),
                code: new R4IssueTypeType('informational'),
                diagnostics: 'No issues found — resource is valid.',
            )],
        );
    }

    /** @param list<FHIRValidationViolation> $violations */
    private function buildR4B(array $violations): R4BOutcome
    {
        return new R4BOutcome(
            issue: $violations !== []
            ? array_map(fn (FHIRValidationViolation $v): R4BIssue => new R4BIssue(
                severity: new R4BIssueSeverityType($this->mapSeverity($v->severity)),
                code: new R4BIssueTypeType($this->mapIssueType($v)),
                diagnostics: $v->message,
                expression: $v->path !== '' ? [$v->path] : [],
            ), $violations)
            : [new R4BIssue(
                severity: new R4BIssueSeverityType('information'),
                code: new R4BIssueTypeType('informational'),
                diagnostics: 'No issues found — resource is valid.',
            )],
        );
    }

    /** @param list<FHIRValidationViolation> $violations */
    private function buildR5(array $violations): R5Outcome
    {
        return new R5Outcome(
            issue: $violations !== []
            ? array_map(fn (FHIRValidationViolation $v): R5Issue => new R5Issue(
                severity: new R5IssueSeverityType($this->mapSeverity($v->severity)),
                code: new R5IssueTypeType($this->mapIssueType($v)),
                diagnostics: $v->message,
                expression: $v->path !== '' ? [$v->path] : [],
            ), $violations)
            : [new R5Issue(
                severity: new R5IssueSeverityType('information'),
                code: new R5IssueTypeType('informational'),
                diagnostics: 'No issues found — resource is valid.',
            )],
        );
    }

    private function mapSeverity(string $severity): string
    {
        return match ($severity) {
            'error'   => 'error',
            'warning' => 'warning',
            default   => 'information',
        };
    }

    private function mapIssueType(FHIRValidationViolation $violation): string
    {
        if ($violation->code               === FHIRViolationCode::EVAL_ERROR
            || $violation->code            === FHIRViolationCode::UNCHECKED_BINDING
            || $violation->constraintClass === FHIRValidationService::class) {
            return 'not-supported';
        }

        if ($violation->code === FHIRViolationCode::INFO) {
            return 'informational';
        }

        return match ($violation->constraintClass) {
            FHIRPathInvariant::class   => 'invariant',
            FHIRValueSetBinding::class => 'value',
            default                    => 'invalid',
        };
    }
}
