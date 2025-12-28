<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceSeverity
 * @description Code type wrapper for FHIRAllergyIntoleranceSeverity enum
 */
class FHIRAllergyIntoleranceSeverityType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceSeverity|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceSeverity|string|null $value = null,
	) {
	}
}
