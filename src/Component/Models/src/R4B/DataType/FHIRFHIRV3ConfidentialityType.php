<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRV3Confidentiality
 * @description Code type wrapper for FHIRV3Confidentiality enum
 */
class FHIRFHIRV3ConfidentialityType extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRV3Confidentiality|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRV3Confidentiality|string|null $value = null,
	) {
	}
}
