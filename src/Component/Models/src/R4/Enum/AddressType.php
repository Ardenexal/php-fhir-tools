<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AddressType
 * URL: http://hl7.org/fhir/ValueSet/address-type
 * Version: 4.0.1
 * Description: The type of an address (physical / postal).
 */
enum AddressType: string
{
    /** Postal */
    case postal = 'postal';

    /** Physical */
    case physical = 'physical';

    /** Postal & Physical */
    case postalandphysical = 'both';
}
