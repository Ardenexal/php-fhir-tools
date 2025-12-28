<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRStructureMapSourceListMode
 * @description Code type wrapper for FHIRStructureMapSourceListMode enum
 */
class FHIRStructureMapSourceListModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapSourceListMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapSourceListMode|string|null $value = null,
	) {
	}
}
