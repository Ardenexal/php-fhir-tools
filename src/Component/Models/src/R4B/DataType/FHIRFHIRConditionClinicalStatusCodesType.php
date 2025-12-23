<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRConditionClinicalStatusCodes
 * @description Code type wrapper for FHIRConditionClinicalStatusCodes enum
 */
class FHIRFHIRConditionClinicalStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConditionClinicalStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConditionClinicalStatusCodes|string|null $value = null,
	) {
	}
}
