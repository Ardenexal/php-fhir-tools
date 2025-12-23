<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAuditEventOutcome
 * @description Code type wrapper for FHIRAuditEventOutcome enum
 */
class FHIRFHIRAuditEventOutcomeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAuditEventOutcome|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAuditEventOutcome|string|null $value = null,
	) {
	}
}
