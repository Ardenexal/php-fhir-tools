<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRLocationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRLocationStatus
 *
 * @description Code type wrapper for FHIRLocationStatus enum
 */
class FHIRLocationStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRLocationStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
