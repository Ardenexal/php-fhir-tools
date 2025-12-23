<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRClaimProcessingCodes
 * @description Code type wrapper for FHIRClaimProcessingCodes enum
 */
class FHIRFHIRClaimProcessingCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRClaimProcessingCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRClaimProcessingCodes|string|null $value = null,
	) {
	}
}
