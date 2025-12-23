<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRAdverseEventOutcome
 * @description Code type wrapper for FHIRAdverseEventOutcome enum
 */
class FHIRFHIRAdverseEventOutcomeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdverseEventOutcome|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdverseEventOutcome|string|null $value = null,
	) {
	}
}
