<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAdverseEventOutcome
 * @description Code type wrapper for FHIRAdverseEventOutcome enum
 */
class FHIRAdverseEventOutcomeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventOutcome|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventOutcome|string|null $value = null,
	) {
	}
}
