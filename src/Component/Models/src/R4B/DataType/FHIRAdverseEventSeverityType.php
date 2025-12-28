<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAdverseEventSeverity
 * @description Code type wrapper for FHIRAdverseEventSeverity enum
 */
class FHIRAdverseEventSeverityType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventSeverity|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventSeverity|string|null $value = null,
	) {
	}
}
