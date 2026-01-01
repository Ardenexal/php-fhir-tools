<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRImmunizationStatusCodes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRImmunizationStatusCodes
 *
 * @description Code type wrapper for FHIRImmunizationStatusCodes enum
 */
class FHIRImmunizationStatusCodesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRImmunizationStatusCodes|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
