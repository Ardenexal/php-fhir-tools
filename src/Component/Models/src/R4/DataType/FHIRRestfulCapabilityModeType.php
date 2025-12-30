<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRestfulCapabilityMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRestfulCapabilityMode
 *
 * @description Code type wrapper for FHIRRestfulCapabilityMode enum
 */
class FHIRRestfulCapabilityModeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRRestfulCapabilityMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
