<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: NutritionProductStatus
 * URL: http://hl7.org/fhir/ValueSet/nutritionproduct-status
 * Version: 4.3.0
 * Description: Codes identifying the lifecycle stage of a product.
 */
enum FHIRNutritionProductStatus: string
{
	/** Active */
	case active = 'active';

	/** Inactive */
	case inactive = 'inactive';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';
}
