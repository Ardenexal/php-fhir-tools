<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireItemType
 * @description Code type wrapper for FHIRQuestionnaireItemType enum
 */
class FHIRQuestionnaireItemTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireItemType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireItemType|string|null $value = null,
	) {
	}
}
