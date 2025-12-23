<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type FHIRReferenceHandlingPolicy
 * @description Code type wrapper for FHIRReferenceHandlingPolicy enum
 */
class FHIRFHIRReferenceHandlingPolicyType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRReferenceHandlingPolicy|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRReferenceHandlingPolicy|string|null $value = null,
	) {
	}
}
