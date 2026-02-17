<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\EndpointStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type EndpointStatus
 *
 * @description Code type wrapper for EndpointStatus enum
 */
class EndpointStatusType extends CodePrimitive
{
    public function __construct(
        /** @param EndpointStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
