<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRImagingSelection2DGraphicType
 * @description Code type wrapper for FHIRImagingSelection2DGraphicType enum
 */
class FHIRFHIRImagingSelection2DGraphicTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRImagingSelection2DGraphicType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRImagingSelection2DGraphicType|string|null $value = null,
	) {
	}
}
