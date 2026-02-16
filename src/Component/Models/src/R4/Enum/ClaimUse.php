<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: Use
 * URL: http://hl7.org/fhir/ValueSet/claim-use
 * Version: 4.0.1
 * Description: The purpose of the Claim: predetermination, preauthorization, claim.
 */
enum ClaimUse: string
{
    /** Claim */
    case claim = 'claim';

    /** Preauthorization */
    case preauthorization = 'preauthorization';

    /** Predetermination */
    case predetermination = 'predetermination';
}
