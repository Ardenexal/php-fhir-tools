<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRMedicationKnowledgeStatusCodes
 * @description Code type wrapper for FHIRMedicationKnowledgeStatusCodes enum
 */
class FHIRMedicationKnowledgeStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationKnowledgeStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMedicationKnowledgeStatusCodes|string|null $value = null,
	) {
	}
}
