<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AddressUse
 * URL: http://hl7.org/fhir/ValueSet/address-use
 * Version: 4.3.0
 * Description: The use of an address.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/address-use', version: '4.3.0')]
enum AddressUse: string
{
    /** Home */
    case home = 'home';

    /** Work */
    case work = 'work';

    /** Temporary */
    case temporary = 'temp';

    /** Old / Incorrect */
    case oldincorrect = 'old';

    /** Billing */
    case billing = 'billing';
}
