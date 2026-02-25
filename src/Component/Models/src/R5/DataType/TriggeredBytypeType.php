<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\TriggeredBytype;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type TriggeredBytype
 *
 * @description Code type wrapper for TriggeredBytype enum
 */
class TriggeredBytypeType extends CodePrimitive
{
    public function __construct(
        /** @param TriggeredBytype|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
