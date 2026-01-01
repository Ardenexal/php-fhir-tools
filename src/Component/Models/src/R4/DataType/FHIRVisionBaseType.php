<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRVisionBase;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRVisionBase
 *
 * @description Code type wrapper for FHIRVisionBase enum
 */
class FHIRVisionBaseType extends FHIRCode
{
    public function __construct(
        /** @param FHIRVisionBase|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
