<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREventCapabilityMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREventCapabilityMode
 *
 * @description Code type wrapper for FHIREventCapabilityMode enum
 */
class FHIREventCapabilityModeType extends FHIRCode
{
    public function __construct(
        /** @param FHIREventCapabilityMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
