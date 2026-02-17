<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: BiologicallyDerivedProductStatus
 * URL: http://hl7.org/fhir/ValueSet/product-status
 * Version: 4.0.1
 * Description: Biologically Derived Product Status.
 */
enum BiologicallyDerivedProductStatus: string
{
    /** Available */
    case available = 'available';

    /** Unavailable */
    case unavailable = 'unavailable';
}
