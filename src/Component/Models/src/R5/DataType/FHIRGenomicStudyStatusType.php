<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRGenomicStudyStatus
 * @description Code type wrapper for FHIRGenomicStudyStatus enum
 */
class FHIRGenomicStudyStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRGenomicStudyStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRGenomicStudyStatus|string|null $value = null,
	) {
	}
}
