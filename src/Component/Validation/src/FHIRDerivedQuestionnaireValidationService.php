<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;

/**
 * Wraps FHIRDerivedQuestionnaireValidator with automatic base resolution.
 *
 * Reads derivedFrom[0] from the derived Questionnaire, resolves the base via
 * the resolver, then delegates to the validator. Returns an empty report when
 * no derivedFrom is present or the canonical URL cannot be resolved.
 *
 * Pass $derivationType explicitly when you know the derivation type (e.g. extracted
 * via FHIRDerivedQuestionnaireValidator::extractDerivationTypeFromJson()). The default
 * 'compliesWith' is conservative — passing a wrong type produces false positives.
 */
final class FHIRDerivedQuestionnaireValidationService
{
    public function __construct(
        private readonly FHIRDerivedQuestionnaireValidator $validator,
        private readonly FHIRQuestionnaireResolverInterface $resolver,
    ) {
    }

    /**
     * Validate a derived Questionnaire against its automatically resolved base.
     *
     * Reads the first derivedFrom canonical URL from $derived, resolves the base
     * Questionnaire via the resolver, and delegates to the validator. Returns an
     * empty report (no violations) when derivedFrom is absent or the URL cannot be resolved.
     *
     * @param QuestionnaireResource $derived        The derived Questionnaire to validate
     * @param string                $derivationType 'compliesWith' | 'extends' | 'inspiredBy'
     *
     * @return FHIRValidationReport Violations found, or an empty report if no base is available
     */
    public function validate(
        QuestionnaireResource $derived,
        string $derivationType = 'compliesWith',
    ): FHIRValidationReport {
        // newInstanceWithoutConstructor() bypasses constructor defaults; ?? guards uninitialized promoted array at runtime
        /** @phpstan-ignore nullCoalesce.property */
        if (($derived->derivedFrom ?? []) === []) {
            return new FHIRValidationReport([]);
        }

        $canonicalUrl = $derived->derivedFrom[0]->value;
        if ($canonicalUrl === null || $canonicalUrl === '') {
            return new FHIRValidationReport([]);
        }

        $base = $this->resolver->resolve($canonicalUrl);
        if ($base === null) {
            return new FHIRValidationReport([]);
        }

        return $this->validator->validate($derived, $base, $derivationType);
    }
}
