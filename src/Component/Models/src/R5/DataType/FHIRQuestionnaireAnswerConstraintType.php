<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireAnswerConstraint
 * @description Code type wrapper for FHIRQuestionnaireAnswerConstraint enum
 */
class FHIRQuestionnaireAnswerConstraintType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRQuestionnaireAnswerConstraint|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRQuestionnaireAnswerConstraint|string|null $value = null,
	) {
	}
}
