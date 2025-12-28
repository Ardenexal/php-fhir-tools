<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceClinicalStatusCodes
 * @description Code type wrapper for FHIRAllergyIntoleranceClinicalStatusCodes enum
 */
class FHIRAllergyIntoleranceClinicalStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceClinicalStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceClinicalStatusCodes|string|null $value = null,
	) {
	}
}
