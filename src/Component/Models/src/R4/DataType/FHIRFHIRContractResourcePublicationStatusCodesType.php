<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRContractResourcePublicationStatusCodes
 * @description Code type wrapper for FHIRContractResourcePublicationStatusCodes enum
 */
class FHIRFHIRContractResourcePublicationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRContractResourcePublicationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRContractResourcePublicationStatusCodes|string|null $value = null,
	) {
	}
}
