<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRClaimProcessingCodes
 * @description Code type wrapper for FHIRClaimProcessingCodes enum
 */
class FHIRClaimProcessingCodesType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRClaimProcessingCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRClaimProcessingCodes|string|null $value = null,
	) {
	}
}
