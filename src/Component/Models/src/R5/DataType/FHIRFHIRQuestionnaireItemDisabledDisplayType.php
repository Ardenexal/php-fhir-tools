<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireItemDisabledDisplay
 * @description Code type wrapper for FHIRQuestionnaireItemDisabledDisplay enum
 */
class FHIRFHIRQuestionnaireItemDisabledDisplayType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRQuestionnaireItemDisabledDisplay|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRQuestionnaireItemDisabledDisplay|string|null $value = null,
	) {
	}
}
