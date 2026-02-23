<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRVersion;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type FHIRVersion
 *
 * @description Code type wrapper for FHIRVersion enum
 */
class FHIRVersionType extends CodePrimitive
{
    public function __construct(
        /** @param FHIRVersion|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
