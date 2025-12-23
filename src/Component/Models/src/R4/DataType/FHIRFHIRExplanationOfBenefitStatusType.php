<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRExplanationOfBenefitStatus
 * @description Code type wrapper for FHIRExplanationOfBenefitStatus enum
 */
class FHIRFHIRExplanationOfBenefitStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRExplanationOfBenefitStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRExplanationOfBenefitStatus|string|null $value = null,
	) {
	}
}
