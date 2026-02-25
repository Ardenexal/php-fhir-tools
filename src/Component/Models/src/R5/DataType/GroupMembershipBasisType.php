<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\GroupMembershipBasis;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type GroupMembershipBasis
 *
 * @description Code type wrapper for GroupMembershipBasis enum
 */
class GroupMembershipBasisType extends CodePrimitive
{
    public function __construct(
        /** @param GroupMembershipBasis|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
