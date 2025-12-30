<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSpecimenStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSpecimenStatus
 *
 * @description Code type wrapper for FHIRSpecimenStatus enum
 */
class FHIRSpecimenStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRSpecimenStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
