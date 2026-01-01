<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRLinkageType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRLinkageType
 *
 * @description Code type wrapper for FHIRLinkageType enum
 */
class FHIRLinkageTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRLinkageType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
