<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRImagingSelectionStatus
 * @description Code type wrapper for FHIRImagingSelectionStatus enum
 */
class FHIRImagingSelectionStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRImagingSelectionStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRImagingSelectionStatus|string|null $value = null,
	) {
	}
}
