<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRArtifactAssessmentInformationType
 * @description Code type wrapper for FHIRArtifactAssessmentInformationType enum
 */
class FHIRArtifactAssessmentInformationTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentInformationType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentInformationType|string|null $value = null,
	) {
	}
}
