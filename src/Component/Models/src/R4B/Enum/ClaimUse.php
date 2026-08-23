<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Use
 * URL: http://hl7.org/fhir/ValueSet/claim-use
 * Version: 4.3.0
 * Description: The purpose of the Claim: predetermination, preauthorization, claim.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/claim-use', version: '4.3.0')]
enum ClaimUse: string
{
    /** Claim */
    case claim = 'claim';

    /** Preauthorization */
    case preauthorization = 'preauthorization';

    /** Predetermination */
    case predetermination = 'predetermination';
}
