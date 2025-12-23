<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-code-type FHIRAuditEventAgentNetworkType
 * @description Code type wrapper for FHIRAuditEventAgentNetworkType enum
 */
class FHIRFHIRAuditEventAgentNetworkTypeType extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode
{
	public function __construct(
		/** @var \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAuditEventAgentNetworkType|string|null $value The code value */
		public \Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAuditEventAgentNetworkType|string|null $value = null,
	) {
	}
}
