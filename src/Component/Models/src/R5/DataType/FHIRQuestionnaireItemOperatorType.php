<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireItemOperator
 * @description Code type wrapper for FHIRQuestionnaireItemOperator enum
 */
class FHIRQuestionnaireItemOperatorType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireItemOperator|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireItemOperator|string|null $value = null,
	) {
	}
}
