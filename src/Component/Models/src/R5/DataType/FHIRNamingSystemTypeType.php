<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRNamingSystemType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNamingSystemType
 *
 * @description Code type wrapper for FHIRNamingSystemType enum
 */
class FHIRNamingSystemTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRNamingSystemType|string|null $value The code value */
        public FHIRNamingSystemType|string|null $value = null,
    ) {
    }
}
