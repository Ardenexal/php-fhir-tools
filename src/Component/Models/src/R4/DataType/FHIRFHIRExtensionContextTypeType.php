<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRExtensionContextType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRExtensionContextType
 *
 * @description Code type wrapper for FHIRExtensionContextType enum
 */
class FHIRFHIRExtensionContextTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRExtensionContextType|string|null $value The code value */
        public FHIRFHIRExtensionContextType|string|null $value = null,
    ) {
    }
}
