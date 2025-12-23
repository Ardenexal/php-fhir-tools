<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRConditionVerificationStatus
 * @description Code type wrapper for FHIRConditionVerificationStatus enum
 */
class FHIRFHIRConditionVerificationStatusType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConditionVerificationStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRConditionVerificationStatus|string|null $value = null,
	) {
	}
}
