<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\IdentityAssuranceLevel;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type IdentityAssuranceLevel
 *
 * @description Code type wrapper for IdentityAssuranceLevel enum
 */
class IdentityAssuranceLevelType extends CodePrimitive
{
    public function __construct(
        /** @param IdentityAssuranceLevel|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
