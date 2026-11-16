<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ConsentProvisionType
 * URL: http://hl7.org/fhir/ValueSet/consent-provision-type
 * Version: 4.0.1
 * Description: How a rule statement is applied, such as adding additional consent or removing consent.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/consent-provision-type', version: '4.0.1')]
enum ConsentProvisionType: string
{
    /** Opt Out */
    case optout = 'deny';

    /** Opt In */
    case optin = 'permit';
}
