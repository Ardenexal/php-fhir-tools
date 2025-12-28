<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceVerificationStatusCodes
 * @description Code type wrapper for FHIRAllergyIntoleranceVerificationStatusCodes enum
 */
class FHIRAllergyIntoleranceVerificationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceVerificationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceVerificationStatusCodes|string|null $value = null,
	) {
	}
}
