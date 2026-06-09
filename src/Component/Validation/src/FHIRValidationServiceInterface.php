<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

interface FHIRValidationServiceInterface
{
    /**
     * Validate a FHIR model object and return a structured report.
     *
     * Base (Default group) constraints are always evaluated. Profile constraints are evaluated only
     * when the matching profile URL is passed in $profileUrls.
     *
     * @param list<string>               $profileUrls            Profile canonical URLs to validate against (empty = base only)
     * @param bool                       $includeMustSupportInfo Emit info-level violations for null/empty must-support properties
     * @param FHIRObligationContext|null $obligationContext      When set, population-class obligations matching the actor produce violations
     */
    public function validate(
        object $resource,
        array $profileUrls = [],
        bool $includeMustSupportInfo = false,
        ?FHIRObligationContext $obligationContext = null,
    ): FHIRValidationReport;

    /**
     * Validate a FHIR resource and return a standards-compliant OperationOutcome.
     *
     * @param string       $mode        '' | 'create' | 'update' | 'profile' | 'delete'
     *                                  'delete' returns an information-severity outcome explaining
     *                                  that referential-integrity checks require a server context
     * @param list<string> $profileUrls profile canonical URLs (used when mode='profile')
     * @param string       $fhirVersion 'R4' | 'R4B' | 'R5'
     *
     * @return object OperationOutcomeResource for the requested FHIR version
     */
    public function validateForOperation(
        object $resource,
        string $mode = '',
        array $profileUrls = [],
        string $fhirVersion = 'R4',
    ): object;
}
