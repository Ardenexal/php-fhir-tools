<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRImagingStudyStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRImagingStudyStatus
 *
 * @description Code type wrapper for FHIRImagingStudyStatus enum
 */
class FHIRImagingStudyStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRImagingStudyStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
