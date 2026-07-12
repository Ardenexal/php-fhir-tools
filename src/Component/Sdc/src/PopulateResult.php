<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

/**
 * The outcome of a `Questionnaire/$populate` run.
 *
 * Holds the generated `QuestionnaireResponse` and a companion `OperationOutcome` of
 * informational/warning issues (e.g. a launch-context-bound expression that returned nothing, or an
 * item type whose coercion is not yet supported). The response and issues are typed as {@see object}
 * rather than version-specific model classes so a single result type spans R4/R4B/R5.
 */
final class PopulateResult
{
    public function __construct(
        /** The generated `QuestionnaireResponse` (a version-specific model). */
        private readonly object $response,
        /** Informational/warning issues raised during population, or null when there were none. */
        private readonly ?object $issues = null,
    ) {
    }

    /**
     * The generated `QuestionnaireResponse`.
     */
    public function getResponse(): object
    {
        return $this->response;
    }

    /**
     * The `OperationOutcome` carrying population issues, or null when there was nothing to report.
     */
    public function getIssues(): ?object
    {
        return $this->issues;
    }
}
