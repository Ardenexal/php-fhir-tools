<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceType
 * @description Code type wrapper for FHIRAllergyIntoleranceType enum
 */
class FHIRFHIRAllergyIntoleranceTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceType|string|null $value = null,
	) {
	}
}
