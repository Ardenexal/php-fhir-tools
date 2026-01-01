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
        /** @param FHIRIdentityAssuranceLevel|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
