<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRImagingSelectionStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRImagingSelectionStatus
 *
 * @description Code type wrapper for FHIRImagingSelectionStatus enum
 */
class FHIRImagingSelectionStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRImagingSelectionStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
