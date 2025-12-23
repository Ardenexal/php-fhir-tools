<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRGenomicStudyStatus
 * @description Code type wrapper for FHIRGenomicStudyStatus enum
 */
class FHIRFHIRGenomicStudyStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRGenomicStudyStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRGenomicStudyStatus|string|null $value = null,
	) {
	}
}
