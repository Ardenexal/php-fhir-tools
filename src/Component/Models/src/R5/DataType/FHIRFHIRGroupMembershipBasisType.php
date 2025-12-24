<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRGroupMembershipBasis;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRGroupMembershipBasis
 *
 * @description Code type wrapper for FHIRGroupMembershipBasis enum
 */
class FHIRFHIRGroupMembershipBasisType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRGroupMembershipBasis|string|null $value The code value */
        public FHIRFHIRGroupMembershipBasis|string|null $value = null,
    ) {
    }
}
