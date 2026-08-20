<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: BiologicallyDerivedProductCategory
 * URL: http://hl7.org/fhir/ValueSet/product-category
 * Version: 4.0.1
 * Description: Biologically Derived Product Category.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/product-category', version: '4.0.1')]
enum BiologicallyDerivedProductCategory: string
{
    /** Organ */
    case organ = 'organ';

    /** Tissue */
    case tissue = 'tissue';

    /** Fluid */
    case fluid = 'fluid';

    /** Cells */
    case cells = 'cells';

    /** BiologicalAgent */
    case biologicalagent = 'biologicalAgent';
}
