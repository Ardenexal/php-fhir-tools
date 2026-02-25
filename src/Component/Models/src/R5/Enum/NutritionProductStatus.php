<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Nutrition Product Status
 * URL: http://hl7.org/fhir/ValueSet/nutritionproduct-status
 * Version: 5.0.0
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
