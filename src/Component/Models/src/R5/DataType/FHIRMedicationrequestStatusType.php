<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRMedicationrequestStatus
 * @description Code type wrapper for FHIRMedicationrequestStatus enum
 */
class FHIRMedicationrequestStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationrequestStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationrequestStatus|string|null $value = null,
	) {
	}
}
