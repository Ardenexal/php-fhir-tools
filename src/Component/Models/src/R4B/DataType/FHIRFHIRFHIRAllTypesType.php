<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRFHIRAllTypes
 * @description Code type wrapper for FHIRFHIRAllTypes enum
 */
class FHIRFHIRFHIRAllTypesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRAllTypes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFHIRAllTypes|string|null $value = null,
	) {
	}
}
