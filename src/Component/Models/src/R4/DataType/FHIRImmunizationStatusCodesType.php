<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRImmunizationStatusCodes
 * @description Code type wrapper for FHIRImmunizationStatusCodes enum
 */
class FHIRImmunizationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRImmunizationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRImmunizationStatusCodes|string|null $value = null,
	) {
	}
}
