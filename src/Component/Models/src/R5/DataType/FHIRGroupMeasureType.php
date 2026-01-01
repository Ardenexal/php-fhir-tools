<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGroupMeasure;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGroupMeasure
 *
 * @description Code type wrapper for FHIRGroupMeasure enum
 */
class FHIRGroupMeasureType extends FHIRCode
{
    public function __construct(
        /** @param FHIRGroupMeasure|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
