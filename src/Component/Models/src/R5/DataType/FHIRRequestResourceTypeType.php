<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRequestResourceType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRequestResourceType
 *
 * @description Code type wrapper for FHIRRequestResourceType enum
 */
class FHIRRequestResourceTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRRequestResourceType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
