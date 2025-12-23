<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireAnswerConstraint
 * @description Code type wrapper for FHIRQuestionnaireAnswerConstraint enum
 */
class FHIRFHIRQuestionnaireAnswerConstraintType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRQuestionnaireAnswerConstraint|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRQuestionnaireAnswerConstraint|string|null $value = null,
	) {
	}
}
