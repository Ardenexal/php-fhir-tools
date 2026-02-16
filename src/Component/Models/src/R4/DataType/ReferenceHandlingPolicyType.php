<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ReferenceHandlingPolicy;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ReferenceHandlingPolicy
 *
 * @description Code type wrapper for ReferenceHandlingPolicy enum
 */
class ReferenceHandlingPolicyType extends CodePrimitive
{
    public function __construct(
        /** @param ReferenceHandlingPolicy|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
