<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRQuestionnaireResponseStatus
 * @description Code type wrapper for FHIRQuestionnaireResponseStatus enum
 */
class FHIRFHIRQuestionnaireResponseStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuestionnaireResponseStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRQuestionnaireResponseStatus|string|null $value = null,
	) {
	}
}
