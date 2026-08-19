<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: AddressType
 * URL: http://hl7.org/fhir/ValueSet/address-type
 * Version: 4.3.0
 * Description: The type of an address (physical / postal).
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/address-type', version: '4.3.0')]
enum AddressType: string
{
    /** Postal */
    case postal = 'postal';

    /** Physical */
    case physical = 'physical';

    /** Postal & Physical */
    case postalandphysical = 'both';
}
