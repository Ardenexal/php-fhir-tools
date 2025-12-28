<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRStructureMapGroupTypeMode
 * @description Code type wrapper for FHIRStructureMapGroupTypeMode enum
 */
class FHIRStructureMapGroupTypeModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapGroupTypeMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapGroupTypeMode|string|null $value = null,
	) {
	}
}
