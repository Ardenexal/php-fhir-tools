<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\GuideParameterCode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type GuideParameterCode
 *
 * @description Code type wrapper for GuideParameterCode enum
 */
class GuideParameterCodeType extends CodePrimitive
{
    public function __construct(
        /** @param GuideParameterCode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
