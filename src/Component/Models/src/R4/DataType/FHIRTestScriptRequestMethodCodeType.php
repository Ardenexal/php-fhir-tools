<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @param FHIRTestScriptRequestMethodCode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
