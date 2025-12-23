<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAdverseEventSeverity
 * @description Code type wrapper for FHIRAdverseEventSeverity enum
 */
class FHIRFHIRAdverseEventSeverityType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdverseEventSeverity|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdverseEventSeverity|string|null $value = null,
	) {
	}
}
