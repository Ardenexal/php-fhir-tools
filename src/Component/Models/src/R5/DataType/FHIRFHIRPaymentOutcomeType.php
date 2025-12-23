<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRPaymentOutcome
 * @description Code type wrapper for FHIRPaymentOutcome enum
 */
class FHIRFHIRPaymentOutcomeType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPaymentOutcome|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRPaymentOutcome|string|null $value = null,
	) {
	}
}
