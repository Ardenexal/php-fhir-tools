<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRStructureMapModelMode
 * @description Code type wrapper for FHIRStructureMapModelMode enum
 */
class FHIRFHIRStructureMapModelModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapModelMode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapModelMode|string|null $value = null,
	) {
	}
}
