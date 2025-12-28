<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRStructureMapTargetListMode
 * @description Code type wrapper for FHIRStructureMapTargetListMode enum
 */
class FHIRStructureMapTargetListModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapTargetListMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapTargetListMode|string|null $value = null,
	) {
	}
}
