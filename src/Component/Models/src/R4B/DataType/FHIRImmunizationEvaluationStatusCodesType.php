<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRImmunizationEvaluationStatusCodes
 * @description Code type wrapper for FHIRImmunizationEvaluationStatusCodes enum
 */
class FHIRImmunizationEvaluationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRImmunizationEvaluationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRImmunizationEvaluationStatusCodes|string|null $value = null,
	) {
	}
}
