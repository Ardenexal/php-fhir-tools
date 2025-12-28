<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRGraphCompartmentUse
 * @description Code type wrapper for FHIRGraphCompartmentUse enum
 */
class FHIRGraphCompartmentUseType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGraphCompartmentUse|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGraphCompartmentUse|string|null $value = null,
	) {
	}
}
