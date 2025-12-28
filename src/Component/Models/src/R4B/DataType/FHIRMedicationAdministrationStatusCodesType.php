<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRMedicationAdministrationStatusCodes
 * @description Code type wrapper for FHIRMedicationAdministrationStatusCodes enum
 */
class FHIRMedicationAdministrationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationAdministrationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationAdministrationStatusCodes|string|null $value = null,
	) {
	}
}
