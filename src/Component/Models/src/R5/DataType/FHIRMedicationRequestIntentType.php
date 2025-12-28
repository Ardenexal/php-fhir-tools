<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRMedicationRequestIntent
 * @description Code type wrapper for FHIRMedicationRequestIntent enum
 */
class FHIRMedicationRequestIntentType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationRequestIntent|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationRequestIntent|string|null $value = null,
	) {
	}
}
