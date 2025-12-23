<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceClinicalStatusCodes
 * @description Code type wrapper for FHIRAllergyIntoleranceClinicalStatusCodes enum
 */
class FHIRFHIRAllergyIntoleranceClinicalStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceClinicalStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceClinicalStatusCodes|string|null $value = null,
	) {
	}
}
