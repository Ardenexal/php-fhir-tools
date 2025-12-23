<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRBenefitCostApplicability
 * @description Code type wrapper for FHIRBenefitCostApplicability enum
 */
class FHIRFHIRBenefitCostApplicabilityType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBenefitCostApplicability|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRBenefitCostApplicability|string|null $value = null,
	) {
	}
}
