<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRMedicationDispenseStatusCodes
 * @description Code type wrapper for FHIRMedicationDispenseStatusCodes enum
 */
class FHIRMedicationDispenseStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationDispenseStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationDispenseStatusCodes|string|null $value = null,
	) {
	}
}
