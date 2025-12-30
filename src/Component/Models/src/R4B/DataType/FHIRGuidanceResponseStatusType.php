<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGuidanceResponseStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGuidanceResponseStatus
 *
 * @description Code type wrapper for FHIRGuidanceResponseStatus enum
 */
class FHIRGuidanceResponseStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRGuidanceResponseStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
