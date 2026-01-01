<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSystemContentMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCodeSystemContentMode
 *
 * @description Code type wrapper for FHIRCodeSystemContentMode enum
 */
class FHIRCodeSystemContentModeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRCodeSystemContentMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
