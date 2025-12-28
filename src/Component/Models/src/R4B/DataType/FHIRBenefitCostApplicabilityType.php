<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRBenefitCostApplicability
 * @description Code type wrapper for FHIRBenefitCostApplicability enum
 */
class FHIRBenefitCostApplicabilityType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBenefitCostApplicability|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRBenefitCostApplicability|string|null $value = null,
	) {
	}
}
