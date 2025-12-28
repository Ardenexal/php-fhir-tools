<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRResearchSubjectState
 * @description Code type wrapper for FHIRResearchSubjectState enum
 */
class FHIRResearchSubjectStateType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRResearchSubjectState|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRResearchSubjectState|string|null $value = null,
	) {
	}
}
