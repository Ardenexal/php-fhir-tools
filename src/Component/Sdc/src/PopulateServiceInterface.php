<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc;

/**
 * Generates a pre-filled `QuestionnaireResponse` from a `Questionnaire` plus contextual data, per the
 * SDC `$populate` operation.
 *
 * The Questionnaire is typed as `object` (not a version-specific class) so a single interface spans
 * R4/R4B/R5 — implementations narrow to {@see PopulateContext::$fhirVersion}. This mirrors the
 * version-agnostic signature the toolkit's validator and `$extract` service already use.
 */
interface PopulateServiceInterface
{
    /**
     * Populate a QuestionnaireResponse from a Questionnaire and its launch context.
     *
     * @param object|string   $questionnaire a version-specific Questionnaire model carrying the SDC
     *                                       population directives (`launchContext`, `initialExpression`), OR
     *                                       a canonical URL string resolved via a configured
     *                                       `FHIRQuestionnaireResolverInterface`
     * @param PopulateContext $context       target version + launch-context resources + subject
     *
     * @return PopulateResult the generated QuestionnaireResponse plus any informational/warning issues
     */
    public function populate(object|string $questionnaire, PopulateContext $context): PopulateResult;
}
