<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRNamingSystemType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNamingSystemType
 *
 * @description Code type wrapper for FHIRNamingSystemType enum
 */
class FHIRFHIRNamingSystemTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRNamingSystemType|string|null $value The code value */
        public FHIRFHIRNamingSystemType|string|null $value = null,
    ) {
    }
}
