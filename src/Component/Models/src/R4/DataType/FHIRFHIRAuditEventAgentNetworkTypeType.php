<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAuditEventAgentNetworkType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAuditEventAgentNetworkType
 *
 * @description Code type wrapper for FHIRAuditEventAgentNetworkType enum
 */
class FHIRFHIRAuditEventAgentNetworkTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAuditEventAgentNetworkType|string|null $value The code value */
        public FHIRFHIRAuditEventAgentNetworkType|string|null $value = null,
    ) {
    }
}
