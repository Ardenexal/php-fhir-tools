<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAuditEventAgentNetworkType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAuditEventAgentNetworkType
 *
 * @description Code type wrapper for FHIRAuditEventAgentNetworkType enum
 */
class FHIRAuditEventAgentNetworkTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAuditEventAgentNetworkType|string|null $value The code value */
        public FHIRAuditEventAgentNetworkType|string|null $value = null,
    ) {
    }
}
