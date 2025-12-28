<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRPaymentOutcome
 * @description Code type wrapper for FHIRPaymentOutcome enum
 */
class FHIRPaymentOutcomeType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRPaymentOutcome|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRPaymentOutcome|string|null $value = null,
	) {
	}
}
