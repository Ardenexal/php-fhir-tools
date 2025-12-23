<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRCountryValueSet
 * @description Code type wrapper for FHIRCountryValueSet enum
 */
class FHIRFHIRCountryValueSetType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRCountryValueSet|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRCountryValueSet|string|null $value = null,
	) {
	}
}
