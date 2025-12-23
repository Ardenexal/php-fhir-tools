<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRImagingSelectionStatus
 * @description Code type wrapper for FHIRImagingSelectionStatus enum
 */
class FHIRFHIRImagingSelectionStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRImagingSelectionStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRImagingSelectionStatus|string|null $value = null,
	) {
	}
}
