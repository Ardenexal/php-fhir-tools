<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRResearchSubjectStatus
 * @description Code type wrapper for FHIRResearchSubjectStatus enum
 */
class FHIRFHIRResearchSubjectStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResearchSubjectStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResearchSubjectStatus|string|null $value = null,
	) {
	}
}
