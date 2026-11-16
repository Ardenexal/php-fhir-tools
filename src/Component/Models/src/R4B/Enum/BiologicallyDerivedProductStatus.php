<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: BiologicallyDerivedProductStatus
 * URL: http://hl7.org/fhir/ValueSet/product-status
 * Version: 4.3.0
 * Description: Biologically Derived Product Status.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/product-status', version: '4.3.0')]
enum BiologicallyDerivedProductStatus: string
{
    /** Available */
    case available = 'available';

    /** Unavailable */
    case unavailable = 'unavailable';
}
