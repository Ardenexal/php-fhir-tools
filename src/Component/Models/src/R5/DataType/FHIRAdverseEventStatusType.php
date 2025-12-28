<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRAdverseEventStatus
 * @description Code type wrapper for FHIRAdverseEventStatus enum
 */
class FHIRAdverseEventStatusType extends \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAdverseEventStatus|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAdverseEventStatus|string|null $value = null,
	) {
	}
}
