<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRClaimProcessingCodes
 * @description Code type wrapper for FHIRClaimProcessingCodes enum
 */
class FHIRClaimProcessingCodesType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRClaimProcessingCodes|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
