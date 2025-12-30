<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRGuideParameterCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRGuideParameterCode
 *
 * @description Code type wrapper for FHIRGuideParameterCode enum
 */
class FHIRGuideParameterCodeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRGuideParameterCode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
