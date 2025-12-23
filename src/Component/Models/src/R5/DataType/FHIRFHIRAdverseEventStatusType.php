<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRAdverseEventStatus
 * @description Code type wrapper for FHIRAdverseEventStatus enum
 */
class FHIRFHIRAdverseEventStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAdverseEventStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAdverseEventStatus|string|null $value = null,
	) {
	}
}
