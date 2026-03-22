<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\SystemRestfulInteraction;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type SystemRestfulInteraction
 *
 * @description Code type wrapper for SystemRestfulInteraction enum
 */
class SystemRestfulInteractionType extends CodePrimitive
{
    public function __construct(
        /** @param SystemRestfulInteraction|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
