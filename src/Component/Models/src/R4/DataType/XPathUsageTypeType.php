<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\XPathUsageType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type XPathUsageType
 *
 * @description Code type wrapper for XPathUsageType enum
 */
class XPathUsageTypeType extends CodePrimitive
{
    public function __construct(
        /** @param XPathUsageType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
