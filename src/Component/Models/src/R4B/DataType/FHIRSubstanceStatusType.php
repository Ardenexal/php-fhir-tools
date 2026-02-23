<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRSubstanceStatus;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type FHIRSubstanceStatus
 *
 * @description Code type wrapper for FHIRSubstanceStatus enum
 */
class FHIRSubstanceStatusType extends CodePrimitive
{
    public function __construct(
        /** @param FHIRSubstanceStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
