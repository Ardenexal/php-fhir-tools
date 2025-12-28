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
        /** @var FHIRGroupMembershipBasis|string|null $value The code value */
        public FHIRGroupMembershipBasis|string|null $value = null,
    ) {
    }
}
