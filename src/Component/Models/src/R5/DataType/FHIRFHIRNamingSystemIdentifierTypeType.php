<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRNamingSystemIdentifierType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRNamingSystemIdentifierType
 *
 * @description Code type wrapper for FHIRNamingSystemIdentifierType enum
 */
class FHIRFHIRNamingSystemIdentifierTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRNamingSystemIdentifierType|string|null $value The code value */
        public FHIRFHIRNamingSystemIdentifierType|string|null $value = null,
    ) {
    }
}
