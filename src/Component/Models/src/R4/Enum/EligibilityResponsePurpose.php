<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: EligibilityResponsePurpose
 * URL: http://hl7.org/fhir/ValueSet/eligibilityresponse-purpose
 * Version: 4.0.1
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
