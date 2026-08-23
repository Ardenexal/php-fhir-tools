<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Nutrition Product Status
 * URL: http://hl7.org/fhir/ValueSet/nutritionproduct-status
 * Version: 5.0.0
 * Description: Codes identifying the lifecycle stage of a product.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/nutritionproduct-status', version: '5.0.0')]
enum NutritionProductStatus: string
{
    /** Active */
    case active = 'active';

    /** Inactive */
    case inactive = 'inactive';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
