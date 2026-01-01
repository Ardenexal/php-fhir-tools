<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSubstanceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFHIRSubstanceStatus
 *
 * @description Code type wrapper for FHIRFHIRSubstanceStatus enum
 */
class FHIRFHIRSubstanceStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRFHIRSubstanceStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
