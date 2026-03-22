<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: NutritionProductStatus
 * URL: http://hl7.org/fhir/ValueSet/nutritionproduct-status
 * Version: 4.3.0
 * Description: Codes identifying the lifecycle stage of a product.
 */
enum NutritionProductStatus: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
