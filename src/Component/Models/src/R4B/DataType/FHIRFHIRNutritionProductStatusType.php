<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRNutritionProductStatus
 * @description Code type wrapper for FHIRNutritionProductStatus enum
 */
class FHIRFHIRNutritionProductStatusType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRNutritionProductStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRNutritionProductStatus|string|null $value = null,
	) {
	}
}
