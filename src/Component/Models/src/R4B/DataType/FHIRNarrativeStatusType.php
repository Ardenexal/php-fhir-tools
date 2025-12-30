<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRNarrativeStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRNarrativeStatus
 *
 * @description Code type wrapper for FHIRNarrativeStatus enum
 */
class FHIRNarrativeStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRNarrativeStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
