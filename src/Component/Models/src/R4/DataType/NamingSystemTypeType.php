<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\NamingSystemType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type NamingSystemType
 *
 * @description Code type wrapper for NamingSystemType enum
 */
class NamingSystemTypeType extends CodePrimitive
{
    public function __construct(
        /** @param NamingSystemType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
