<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRGraphCompartmentUse
 * @description Code type wrapper for FHIRGraphCompartmentUse enum
 */
class FHIRFHIRGraphCompartmentUseType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGraphCompartmentUse|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRGraphCompartmentUse|string|null $value = null,
	) {
	}
}
