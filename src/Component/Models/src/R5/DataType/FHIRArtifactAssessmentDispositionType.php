<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRArtifactAssessmentDisposition
 * @description Code type wrapper for FHIRArtifactAssessmentDisposition enum
 */
class FHIRArtifactAssessmentDispositionType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentDisposition|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRArtifactAssessmentDisposition|string|null $value = null,
	) {
	}
}
