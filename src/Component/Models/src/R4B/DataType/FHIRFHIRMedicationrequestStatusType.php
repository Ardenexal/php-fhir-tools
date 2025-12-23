<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRMedicationrequestStatus
 * @description Code type wrapper for FHIRMedicationrequestStatus enum
 */
class FHIRFHIRMedicationrequestStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationrequestStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMedicationrequestStatus|string|null $value = null,
	) {
	}
}
