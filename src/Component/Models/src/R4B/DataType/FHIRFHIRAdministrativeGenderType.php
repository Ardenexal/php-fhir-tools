<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAdministrativeGender;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAdministrativeGender
 *
 * @description Code type wrapper for FHIRAdministrativeGender enum
 */
class FHIRFHIRAdministrativeGenderType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAdministrativeGender|string|null $value The code value */
        public FHIRFHIRAdministrativeGender|string|null $value = null,
    ) {
    }
}
