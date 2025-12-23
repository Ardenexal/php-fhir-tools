<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRAllergyIntoleranceCriticality
 * @description Code type wrapper for FHIRAllergyIntoleranceCriticality enum
 */
class FHIRFHIRAllergyIntoleranceCriticalityType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceCriticality|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAllergyIntoleranceCriticality|string|null $value = null,
	) {
	}
}
