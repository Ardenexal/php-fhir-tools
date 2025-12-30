<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRStructureMapContextType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapContextType
 *
 * @description Code type wrapper for FHIRStructureMapContextType enum
 */
class FHIRStructureMapContextTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRStructureMapContextType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
