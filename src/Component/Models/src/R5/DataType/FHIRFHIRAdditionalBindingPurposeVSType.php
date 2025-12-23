<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRAdditionalBindingPurposeVS
 * @description Code type wrapper for FHIRAdditionalBindingPurposeVS enum
 */
class FHIRFHIRAdditionalBindingPurposeVSType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAdditionalBindingPurposeVS|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAdditionalBindingPurposeVS|string|null $value = null,
	) {
	}
}
