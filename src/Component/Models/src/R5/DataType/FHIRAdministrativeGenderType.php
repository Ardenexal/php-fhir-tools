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
        /** @param FHIRAdministrativeGender|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
