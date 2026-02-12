<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-code-type AuditEventOutcome
 * @description Code type wrapper for AuditEventOutcome enum
 */
class AuditEventOutcomeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\AuditEventOutcome|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
