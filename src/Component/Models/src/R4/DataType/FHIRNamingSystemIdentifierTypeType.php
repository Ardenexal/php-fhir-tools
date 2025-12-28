<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @var FHIRNamingSystemIdentifierType|string|null $value The code value */
        public FHIRNamingSystemIdentifierType|string|null $value = null,
    ) {
    }
}
