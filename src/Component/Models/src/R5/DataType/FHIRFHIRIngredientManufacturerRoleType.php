<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRIngredientManufacturerRole
 * @description Code type wrapper for FHIRIngredientManufacturerRole enum
 */
class FHIRFHIRIngredientManufacturerRoleType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRIngredientManufacturerRole|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRIngredientManufacturerRole|string|null $value = null,
	) {
	}
}
