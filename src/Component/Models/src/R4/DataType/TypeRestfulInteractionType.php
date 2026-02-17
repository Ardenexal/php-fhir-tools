<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\TypeRestfulInteraction;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type TypeRestfulInteraction
 *
 * @description Code type wrapper for TypeRestfulInteraction enum
 */
class TypeRestfulInteractionType extends CodePrimitive
{
    public function __construct(
        /** @param TypeRestfulInteraction|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
