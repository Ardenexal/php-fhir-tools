<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRSearchModifierCode
 * @description Code type wrapper for FHIRSearchModifierCode enum
 */
class FHIRFHIRSearchModifierCodeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSearchModifierCode|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSearchModifierCode|string|null $value = null,
	) {
	}
}
