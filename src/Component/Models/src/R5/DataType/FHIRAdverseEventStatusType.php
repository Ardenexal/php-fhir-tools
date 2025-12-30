<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAdverseEventStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAdverseEventStatus
 *
 * @description Code type wrapper for FHIRAdverseEventStatus enum
 */
class FHIRAdverseEventStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAdverseEventStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
