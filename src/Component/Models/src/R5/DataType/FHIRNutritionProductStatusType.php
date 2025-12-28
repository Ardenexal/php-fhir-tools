<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRNutritionProductStatus
 * @description Code type wrapper for FHIRNutritionProductStatus enum
 */
class FHIRNutritionProductStatusType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRNutritionProductStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRNutritionProductStatus|string|null $value = null,
	) {
	}
}
