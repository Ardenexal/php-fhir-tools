<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRCharacteristicCombination
 * @description Code type wrapper for FHIRCharacteristicCombination enum
 */
class FHIRCharacteristicCombinationType extends \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRCharacteristicCombination|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRCharacteristicCombination|string|null $value = null,
	) {
	}
}
