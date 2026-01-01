<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAdverseEventActuality;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventActuality
 *
 * @description Code type wrapper for FHIRAdverseEventActuality enum
 */
class FHIRAdverseEventActualityType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAdverseEventActuality|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
