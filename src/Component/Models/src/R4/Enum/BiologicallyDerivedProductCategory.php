<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: BiologicallyDerivedProductCategory
 * URL: http://hl7.org/fhir/ValueSet/product-category
 * Version: 4.0.1
 * Description: Biologically Derived Product Category.
 */
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
