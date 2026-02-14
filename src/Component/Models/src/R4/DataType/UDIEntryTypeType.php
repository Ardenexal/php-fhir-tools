<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\UDIEntryType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type UDIEntryType
 *
 * @description Code type wrapper for UDIEntryType enum
 */
class UDIEntryTypeType extends CodePrimitive
{
    public function __construct(
        /** @param UDIEntryType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
