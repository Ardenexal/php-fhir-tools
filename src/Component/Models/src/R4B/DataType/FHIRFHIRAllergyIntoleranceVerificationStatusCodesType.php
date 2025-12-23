<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceVerificationStatusCodes
 * @description Code type wrapper for FHIRAllergyIntoleranceVerificationStatusCodes enum
 */
class FHIRFHIRAllergyIntoleranceVerificationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceVerificationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceVerificationStatusCodes|string|null $value = null,
	) {
	}
}
