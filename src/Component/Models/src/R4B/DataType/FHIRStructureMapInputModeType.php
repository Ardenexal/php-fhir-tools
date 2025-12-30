<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapInputMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapInputMode
 *
 * @description Code type wrapper for FHIRStructureMapInputMode enum
 */
class FHIRStructureMapInputModeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRStructureMapInputMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
