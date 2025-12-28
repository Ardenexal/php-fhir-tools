<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireItemDisabledDisplay
 * @description Code type wrapper for FHIRQuestionnaireItemDisabledDisplay enum
 */
class FHIRQuestionnaireItemDisabledDisplayType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRQuestionnaireItemDisabledDisplay|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRQuestionnaireItemDisabledDisplay|string|null $value = null,
	) {
	}
}
