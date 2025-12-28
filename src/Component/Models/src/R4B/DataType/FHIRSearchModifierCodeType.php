<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRSearchModifierCode
 * @description Code type wrapper for FHIRSearchModifierCode enum
 */
class FHIRSearchModifierCodeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSearchModifierCode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSearchModifierCode|string|null $value = null,
	) {
	}
}
