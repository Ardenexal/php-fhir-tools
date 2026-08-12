<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: EligibilityRequestPurpose
 * URL: http://hl7.org/fhir/ValueSet/eligibilityrequest-purpose
 * Version: 4.0.1
 * Description: A code specifying the types of information being requested.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/eligibilityrequest-purpose', version: '4.0.1')]
enum EligibilityRequestPurpose: string
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
