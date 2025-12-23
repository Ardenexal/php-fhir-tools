<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRImmunizationEvaluationStatusCodes
 * @description Code type wrapper for FHIRImmunizationEvaluationStatusCodes enum
 */
class FHIRFHIRImmunizationEvaluationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRImmunizationEvaluationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRImmunizationEvaluationStatusCodes|string|null $value = null,
	) {
	}
}
