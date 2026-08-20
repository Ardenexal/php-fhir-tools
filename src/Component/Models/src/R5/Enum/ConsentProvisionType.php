<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Consent Provision Type
 * URL: http://hl7.org/fhir/ValueSet/consent-provision-type
 * Version: 5.0.0
 * Description: How a rule statement is applied, such as adding additional consent or removing consent.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/consent-provision-type', version: '5.0.0')]
enum ConsentProvisionType: string
{
    /** Deny */
    case deny = 'deny';

    /** Permit */
    case permit = 'permit';
}
