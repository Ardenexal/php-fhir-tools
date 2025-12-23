<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRArtifactAssessmentInformationType
 * @description Code type wrapper for FHIRArtifactAssessmentInformationType enum
 */
class FHIRFHIRArtifactAssessmentInformationTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRArtifactAssessmentInformationType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRArtifactAssessmentInformationType|string|null $value = null,
	) {
	}
}
