<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;

/**
 * In-memory implementation of FHIRQuestionnaireResolverInterface.
 *
 * Indexes a fixed list of Questionnaire objects by their canonical URL at construction
 * time. Use this when you already have the base Questionnaire objects loaded in memory
 * and need to resolve them by URL during validation.
 */
final class InMemoryFHIRQuestionnaireResolver implements FHIRQuestionnaireResolverInterface
{
    /** @var array<string, QuestionnaireResource> */
    private readonly array $index;

    /**
     * Build the URL index from the provided Questionnaire list.
     *
     * Questionnaires without a url value are silently skipped.
     *
     * @param list<QuestionnaireResource> $questionnaires Questionnaires to index
     */
    public function __construct(array $questionnaires)
    {
        $index = [];
        foreach ($questionnaires as $q) {
            $url = isset($q->url) ? $q->url->value : null;
            if ($url !== null && $url !== '') {
                $index[$url] = $q;
            }
        }
        $this->index = $index;
    }

    /**
     * Return the Questionnaire whose url matches the given canonical URL exactly.
     *
     * @param string $canonicalUrl Exact canonical URL to look up (version suffixes are not stripped)
     *
     * @return QuestionnaireResource|null The matching Questionnaire, or null if not found
     */
    public function resolve(string $canonicalUrl): ?QuestionnaireResource
    {
        return $this->index[$canonicalUrl] ?? null;
    }
}
