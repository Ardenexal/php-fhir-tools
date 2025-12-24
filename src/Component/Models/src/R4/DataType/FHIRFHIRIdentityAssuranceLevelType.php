<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRIdentityAssuranceLevel;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRIdentityAssuranceLevel
 *
 * @description Code type wrapper for FHIRIdentityAssuranceLevel enum
 */
class FHIRFHIRIdentityAssuranceLevelType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRIdentityAssuranceLevel|string|null $value The code value */
        public FHIRFHIRIdentityAssuranceLevel|string|null $value = null,
    ) {
    }
}
