<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRActionPrecheckBehavior;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRActionPrecheckBehavior
 *
 * @description Code type wrapper for FHIRActionPrecheckBehavior enum
 */
class FHIRActionPrecheckBehaviorType extends FHIRCode
{
    public function __construct(
        /** @param FHIRActionPrecheckBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
