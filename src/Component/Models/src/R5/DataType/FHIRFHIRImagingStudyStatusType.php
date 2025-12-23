<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRImagingStudyStatus
 * @description Code type wrapper for FHIRImagingStudyStatus enum
 */
class FHIRFHIRImagingStudyStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRImagingStudyStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRImagingStudyStatus|string|null $value = null,
	) {
	}
}
