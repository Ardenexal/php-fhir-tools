<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Validates a QuestionnaireResponse against its source Questionnaire.
 *
 * Covers the structural rules defined by the FHIR specification: linkId matching, required
 * items, repeats cardinality, answer type conformance, and basic enableWhen evaluation.
 * See ADR-007 for the architecture decision (standalone service, not part of
 * FHIRValidationService); run both services and merge reports for full validation.
 */
interface FHIRQuestionnaireValidatorInterface
{
    /**
     * @param object $questionnaire The source QuestionnaireResource (R4, R4B, or R5)
     * @param object $response      The QuestionnaireResponseResource to validate (R4, R4B, or R5)
     * @param bool   $strictStatus  When false, the required-item check is skipped regardless of
     *                              the response status; when true it applies only to responses
     *                              with status completed or amended
     *
     * @return FHIRValidationReport A structured report containing all violations found
     *
     * @throws \InvalidArgumentException When either argument is not a supported resource type
     */
    public function validate(
        object $questionnaire,
        object $response,
        bool $strictStatus = true,
    ): FHIRValidationReport;
}
