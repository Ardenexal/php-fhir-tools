<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestScriptRequestMethodCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTestScriptRequestMethodCode
 *
 * @description Code type wrapper for FHIRTestScriptRequestMethodCode enum
 */
class FHIRTestScriptRequestMethodCodeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTestScriptRequestMethodCode|string|null $value The code value */
        public FHIRTestScriptRequestMethodCode|string|null $value = null,
    ) {
    }
}
