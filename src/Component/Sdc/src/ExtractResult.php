<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

/**
 * The outcome of a `QuestionnaireResponse/$extract` run.
 *
 * Holds the extracted payload and a companion `OperationOutcome` of informational/warning issues. The
 * payload is a FHIR resource: for observation-based (and definition/template-based) extraction it is a
 * transaction `Bundle` — always, even for a single extracted resource (per the SDC extraction spec) —
 * but the type is left as {@see AbstractResource}-shaped `object` so future single-resource outputs
 * and cross-version model classes fit without a signature change.
 */
final class ExtractResult
{
    public function __construct(
        /** The extracted payload — a transaction Bundle for observation-based extraction. */
        private readonly object $resource,
        /** Informational/warning issues raised during extraction (e.g. "nothing extracted"), or null. */
        private readonly ?object $issues = null,
    ) {
    }

    /**
     * The extracted resource — a transaction `Bundle` for observation-based extraction.
     */
    public function getResource(): object
    {
        return $this->resource;
    }

    /**
     * The `OperationOutcome` carrying extraction issues, or null when there was nothing to report.
     */
    public function getIssues(): ?object
    {
        return $this->issues;
    }
}
