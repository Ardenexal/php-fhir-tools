<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResearchElementType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResearchElementType
 *
 * @description Code type wrapper for FHIRResearchElementType enum
 */
class FHIRResearchElementTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRResearchElementType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
