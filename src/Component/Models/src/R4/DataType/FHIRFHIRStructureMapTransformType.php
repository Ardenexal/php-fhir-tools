<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRStructureMapTransform
 * @description Code type wrapper for FHIRStructureMapTransform enum
 */
class FHIRFHIRStructureMapTransformType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapTransform|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapTransform|string|null $value = null,
	) {
	}
}
