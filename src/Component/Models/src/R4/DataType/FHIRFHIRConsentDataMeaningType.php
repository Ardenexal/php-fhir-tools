<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRConsentDataMeaning
 * @description Code type wrapper for FHIRConsentDataMeaning enum
 */
class FHIRFHIRConsentDataMeaningType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConsentDataMeaning|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConsentDataMeaning|string|null $value = null,
	) {
	}
}
