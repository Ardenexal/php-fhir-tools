<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: BiologicallyDerivedProductStorageScale
 * URL: http://hl7.org/fhir/ValueSet/product-storage-scale
 * Version: 4.3.0
 * Description: BiologicallyDerived Product Storage Scale.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/product-storage-scale', version: '4.3.0')]
enum BiologicallyDerivedProductStorageScale: string
{
    /** Fahrenheit */
    case fahrenheit = 'farenheit';

    /** Celsius */
    case celsius = 'celsius';

    /** Kelvin */
    case kelvin = 'kelvin';
}
