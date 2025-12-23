<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIREligibilityOutcome
 * @description Code type wrapper for FHIREligibilityOutcome enum
 */
class FHIRFHIREligibilityOutcomeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIREligibilityOutcome|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIREligibilityOutcome|string|null $value = null,
	) {
	}
}
