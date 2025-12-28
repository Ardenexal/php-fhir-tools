<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireResponseStatus
 * @description Code type wrapper for FHIRQuestionnaireResponseStatus enum
 */
class FHIRQuestionnaireResponseStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireResponseStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRQuestionnaireResponseStatus|string|null $value = null,
	) {
	}
}
