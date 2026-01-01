<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAuditEventAgentNetworkType
 * @description Code type wrapper for FHIRAuditEventAgentNetworkType enum
 */
class FHIRAuditEventAgentNetworkTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode
{
	public function __construct(
		/** @param \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAuditEventAgentNetworkType|string|null $value The code value (enum or string) */
		string|null $value = null,
	) {
		parent::__construct(value: $value);
	}
}
