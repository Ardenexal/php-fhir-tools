<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRMedicationDispenseStatusCodes
 * @description Code type wrapper for FHIRMedicationDispenseStatusCodes enum
 */
class FHIRFHIRMedicationDispenseStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationDispenseStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationDispenseStatusCodes|string|null $value = null,
	) {
	}
}
