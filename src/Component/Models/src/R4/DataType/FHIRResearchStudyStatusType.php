<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRResearchStudyStatus
 * @description Code type wrapper for FHIRResearchStudyStatus enum
 */
class FHIRResearchStudyStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResearchStudyStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResearchStudyStatus|string|null $value = null,
	) {
	}
}
