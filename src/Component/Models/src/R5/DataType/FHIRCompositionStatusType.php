<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCompositionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCompositionStatus
 *
 * @description Code type wrapper for FHIRCompositionStatus enum
 */
class FHIRCompositionStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRCompositionStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
