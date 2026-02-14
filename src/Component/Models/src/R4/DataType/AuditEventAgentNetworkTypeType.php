<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\AuditEventAgentNetworkType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type AuditEventAgentNetworkType
 *
 * @description Code type wrapper for AuditEventAgentNetworkType enum
 */
class AuditEventAgentNetworkTypeType extends CodePrimitive
{
    public function __construct(
        /** @param AuditEventAgentNetworkType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
