<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRContactPointSystem;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRContactPointSystem
 *
 * @description Code type wrapper for FHIRContactPointSystem enum
 */
class FHIRFHIRContactPointSystemType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRContactPointSystem|string|null $value The code value */
        public FHIRFHIRContactPointSystem|string|null $value = null,
    ) {
    }
}
