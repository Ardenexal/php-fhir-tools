<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRStructureMapInputMode
 * @description Code type wrapper for FHIRStructureMapInputMode enum
 */
class FHIRStructureMapInputModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapInputMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapInputMode|string|null $value = null,
	) {
	}
}
