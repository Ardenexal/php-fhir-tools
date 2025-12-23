<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRMedicationStatusCodes
 * @description Code type wrapper for FHIRMedicationStatusCodes enum
 */
class FHIRFHIRMedicationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationStatusCodes|string|null $value = null,
	) {
	}
}
