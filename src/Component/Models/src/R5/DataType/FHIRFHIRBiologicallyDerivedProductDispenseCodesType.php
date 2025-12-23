<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRBiologicallyDerivedProductDispenseCodes
 * @description Code type wrapper for FHIRBiologicallyDerivedProductDispenseCodes enum
 */
class FHIRFHIRBiologicallyDerivedProductDispenseCodesType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRBiologicallyDerivedProductDispenseCodes|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRBiologicallyDerivedProductDispenseCodes|string|null $value = null,
	) {
	}
}
