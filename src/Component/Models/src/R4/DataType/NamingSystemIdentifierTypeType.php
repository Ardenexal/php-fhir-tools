<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\NamingSystemIdentifierType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type NamingSystemIdentifierType
 *
 * @description Code type wrapper for NamingSystemIdentifierType enum
 */
class NamingSystemIdentifierTypeType extends CodePrimitive
{
    public function __construct(
        /** @param NamingSystemIdentifierType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
