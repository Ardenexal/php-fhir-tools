<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRContactPointSystem;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRContactPointSystem
 *
 * @description Code type wrapper for FHIRContactPointSystem enum
 */
class FHIRContactPointSystemType extends FHIRCode
{
    public function __construct(
        /** @var FHIRContactPointSystem|string|null $value The code value */
        public FHIRContactPointSystem|string|null $value = null,
    ) {
    }
}
