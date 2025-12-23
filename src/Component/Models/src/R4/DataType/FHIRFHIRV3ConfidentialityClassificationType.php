<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRV3ConfidentialityClassification
 * @description Code type wrapper for FHIRV3ConfidentialityClassification enum
 */
class FHIRFHIRV3ConfidentialityClassificationType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRV3ConfidentialityClassification|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRV3ConfidentialityClassification|string|null $value = null,
	) {
	}
}
