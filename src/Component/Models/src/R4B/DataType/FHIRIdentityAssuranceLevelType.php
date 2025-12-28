<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRIdentityAssuranceLevel;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRIdentityAssuranceLevel
 *
 * @description Code type wrapper for FHIRIdentityAssuranceLevel enum
 */
class FHIRIdentityAssuranceLevelType extends FHIRCode
{
    public function __construct(
        /** @var FHIRIdentityAssuranceLevel|string|null $value The code value */
        public FHIRIdentityAssuranceLevel|string|null $value = null,
    ) {
    }
}
