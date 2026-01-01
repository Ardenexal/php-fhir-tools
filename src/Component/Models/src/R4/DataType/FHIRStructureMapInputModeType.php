<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRStructureMapInputMode
 * @description Code type wrapper for FHIRStructureMapInputMode enum
 */
class FHIRStructureMapInputModeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapInputMode|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
