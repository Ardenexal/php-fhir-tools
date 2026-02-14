<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\EventCapabilityMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type EventCapabilityMode
 *
 * @description Code type wrapper for EventCapabilityMode enum
 */
class EventCapabilityModeType extends CodePrimitive
{
    public function __construct(
        /** @param EventCapabilityMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
