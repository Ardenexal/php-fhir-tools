<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRMedicationStatusCodes
 * @description Code type wrapper for FHIRMedicationStatusCodes enum
 */
class FHIRMedicationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRMedicationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRMedicationStatusCodes|string|null $value = null,
	) {
	}
}
