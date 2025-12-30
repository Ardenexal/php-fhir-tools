<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMessageheaderResponseRequest;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMessageheaderResponseRequest
 *
 * @description Code type wrapper for FHIRMessageheaderResponseRequest enum
 */
class FHIRMessageheaderResponseRequestType extends FHIRCode
{
    public function __construct(
        /** @param FHIRMessageheaderResponseRequest|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
