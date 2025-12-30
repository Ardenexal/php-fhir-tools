<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRCodeSearchSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCodeSearchSupport
 *
 * @description Code type wrapper for FHIRCodeSearchSupport enum
 */
class FHIRCodeSearchSupportType extends FHIRCode
{
    public function __construct(
        /** @param FHIRCodeSearchSupport|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
