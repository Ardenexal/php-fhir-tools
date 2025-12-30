<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRNamingSystemIdentifierType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNamingSystemIdentifierType
 *
 * @description Code type wrapper for FHIRNamingSystemIdentifierType enum
 */
class FHIRNamingSystemIdentifierTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRNamingSystemIdentifierType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
