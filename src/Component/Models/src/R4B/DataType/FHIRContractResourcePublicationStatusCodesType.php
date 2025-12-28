<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRContractResourcePublicationStatusCodes
 * @description Code type wrapper for FHIRContractResourcePublicationStatusCodes enum
 */
class FHIRContractResourcePublicationStatusCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRContractResourcePublicationStatusCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRContractResourcePublicationStatusCodes|string|null $value = null,
	) {
	}
}
