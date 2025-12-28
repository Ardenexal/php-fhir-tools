<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRTypeDerivationRule
 * @description Code type wrapper for FHIRTypeDerivationRule enum
 */
class FHIRTypeDerivationRuleType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTypeDerivationRule|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTypeDerivationRule|string|null $value = null,
	) {
	}
}
