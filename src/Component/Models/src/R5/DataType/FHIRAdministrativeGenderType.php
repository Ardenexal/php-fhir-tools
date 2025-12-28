<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdministrativeGender;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdministrativeGender
 *
 * @description Code type wrapper for FHIRAdministrativeGender enum
 */
class FHIRAdministrativeGenderType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAdministrativeGender|string|null $value The code value */
        public FHIRAdministrativeGender|string|null $value = null,
    ) {
    }
}
