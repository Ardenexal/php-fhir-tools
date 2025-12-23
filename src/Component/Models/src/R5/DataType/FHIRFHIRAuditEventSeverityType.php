<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-code-type FHIRAuditEventSeverity
 * @description Code type wrapper for FHIRAuditEventSeverity enum
 */
class FHIRFHIRAuditEventSeverityType extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAuditEventSeverity|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAuditEventSeverity|string|null $value = null,
	) {
	}
}
