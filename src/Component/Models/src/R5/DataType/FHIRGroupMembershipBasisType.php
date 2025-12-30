<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRGroupMembershipBasis;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGroupMembershipBasis
 *
 * @description Code type wrapper for FHIRGroupMembershipBasis enum
 */
class FHIRGroupMembershipBasisType extends FHIRCode
{
    public function __construct(
        /** @param FHIRGroupMembershipBasis|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
