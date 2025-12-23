<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireItemOperator
 * @description Code type wrapper for FHIRQuestionnaireItemOperator enum
 */
class FHIRFHIRQuestionnaireItemOperatorType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuestionnaireItemOperator|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuestionnaireItemOperator|string|null $value = null,
	) {
	}
}
