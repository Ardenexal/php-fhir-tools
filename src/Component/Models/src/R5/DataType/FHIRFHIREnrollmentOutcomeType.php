<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIREnrollmentOutcome
 * @description Code type wrapper for FHIREnrollmentOutcome enum
 */
class FHIRFHIREnrollmentOutcomeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIREnrollmentOutcome|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIREnrollmentOutcome|string|null $value = null,
	) {
	}
}
