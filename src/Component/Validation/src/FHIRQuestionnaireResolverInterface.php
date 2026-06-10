<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;

/**
 * Resolves a FHIR Questionnaire resource by its canonical URL.
 *
 * Implement this interface to provide the validation layer with base Questionnaire
 * objects when only a canonical URL string is available (e.g. from derivedFrom).
 */
interface FHIRQuestionnaireResolverInterface
{
    /**
     * Look up a Questionnaire by its canonical URL.
     *
     * @param string $canonicalUrl The canonical URL to resolve (e.g. "http://example.org/q-base")
     *
     * @return QuestionnaireResource|null The matching Questionnaire, or null if not found
     */
    public function resolve(string $canonicalUrl): ?QuestionnaireResource;
}
