<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ResourceVersionPolicy;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ResourceVersionPolicy
 *
 * @description Code type wrapper for ResourceVersionPolicy enum
 */
class ResourceVersionPolicyType extends CodePrimitive
{
    public function __construct(
        /** @param ResourceVersionPolicy|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
