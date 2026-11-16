<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Contract;

use Ardenexal\FHIRTools\Component\Sdc\ExtractContext;
use Ardenexal\FHIRTools\Component\Sdc\ExtractResult;

/**
 * Transforms a completed `QuestionnaireResponse` into FHIR resources per the SDC `$extract` operation.
 *
 * The response is typed as `object` (not a version-specific `QuestionnaireResponse` class) so a single
 * interface spans R4/R4B/R5 — implementations narrow to their target version and honour
 * {@see ExtractContext::$fhirVersion}. This mirrors the version-agnostic signature the toolkit's
 * validator already uses.
 */
interface ExtractServiceInterface
{
    /**
     * Extract FHIR resources from a completed QuestionnaireResponse.
     *
     * @param object         $questionnaireResponse the completed response (a version-specific QuestionnaireResponse model)
     * @param ExtractContext $context               target version + the source Questionnaire carrying extract directives
     *
     * @return ExtractResult the extracted transaction Bundle plus any informational issues
     */
    public function extract(object $questionnaireResponse, ExtractContext $context): ExtractResult;
}
