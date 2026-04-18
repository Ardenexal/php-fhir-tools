<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Eligibility Response Purpose
 * URL: http://hl7.org/fhir/ValueSet/eligibilityresponse-purpose
 * Version: 5.0.0
 * Description: A code specifying the types of information being requested.
 */
enum EligibilityResponsePurpose: string
{
    /** Coverage auth-requirements */
    case coverageauthrequirements = 'auth-requirements';

    /** Coverage benefits */
    case coveragebenefits = 'benefits';

    /** Coverage Discovery */
    case coveragediscovery = 'discovery';

    /** Coverage Validation */
    case coveragevalidation = 'validation';
}
