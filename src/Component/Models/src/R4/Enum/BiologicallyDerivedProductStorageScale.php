<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: BiologicallyDerivedProductStorageScale
 * URL: http://hl7.org/fhir/ValueSet/product-storage-scale
 * Version: 4.0.1
 * Description: BiologicallyDerived Product Storage Scale.
 */
enum BiologicallyDerivedProductStorageScale: string
{
    /** Fahrenheit */
    case fahrenheit = 'farenheit';

    /** Celsius */
    case celsius = 'celsius';

    /** Kelvin */
    case kelvin = 'kelvin';
}
