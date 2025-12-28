<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceType
 * @description Code type wrapper for FHIRAllergyIntoleranceType enum
 */
class FHIRAllergyIntoleranceTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAllergyIntoleranceType|string|null $value = null,
	) {
	}
}
