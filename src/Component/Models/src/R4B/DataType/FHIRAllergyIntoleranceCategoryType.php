<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceCategory
 * @description Code type wrapper for FHIRAllergyIntoleranceCategory enum
 */
class FHIRAllergyIntoleranceCategoryType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceCategory|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceCategory|string|null $value = null,
	) {
	}
}
