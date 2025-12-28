<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRImagingSelection3DGraphicType
 * @description Code type wrapper for FHIRImagingSelection3DGraphicType enum
 */
class FHIRImagingSelection3DGraphicTypeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRImagingSelection3DGraphicType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRImagingSelection3DGraphicType|string|null $value = null,
	) {
	}
}
