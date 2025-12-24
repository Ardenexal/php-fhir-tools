<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRStructureMapContextType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRStructureMapContextType
 *
 * @description Code type wrapper for FHIRStructureMapContextType enum
 */
class FHIRFHIRStructureMapContextTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRStructureMapContextType|string|null $value The code value */
        public FHIRFHIRStructureMapContextType|string|null $value = null,
    ) {
    }
}
