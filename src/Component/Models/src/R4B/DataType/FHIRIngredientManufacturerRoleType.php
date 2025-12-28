<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRIngredientManufacturerRole
 * @description Code type wrapper for FHIRIngredientManufacturerRole enum
 */
class FHIRIngredientManufacturerRoleType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRIngredientManufacturerRole|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRIngredientManufacturerRole|string|null $value = null,
	) {
	}
}
