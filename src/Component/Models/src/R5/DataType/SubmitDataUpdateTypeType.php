<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\SubmitDataUpdateType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type SubmitDataUpdateType
 *
 * @description Code type wrapper for SubmitDataUpdateType enum
 */
class SubmitDataUpdateTypeType extends CodePrimitive
{
    public function __construct(
        /** @param SubmitDataUpdateType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
