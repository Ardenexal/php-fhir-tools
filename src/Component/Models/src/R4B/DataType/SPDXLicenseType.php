<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\SPDXLicense;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type SPDXLicense
 *
 * @description Code type wrapper for SPDXLicense enum
 */
class SPDXLicenseType extends CodePrimitive
{
    public function __construct(
        /** @param SPDXLicense|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
