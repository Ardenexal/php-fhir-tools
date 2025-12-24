<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRImagingSelectionStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRImagingSelectionStatus
 *
 * @description Code type wrapper for FHIRImagingSelectionStatus enum
 */
class FHIRFHIRImagingSelectionStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRImagingSelectionStatus|string|null $value The code value */
        public FHIRFHIRImagingSelectionStatus|string|null $value = null,
    ) {
    }
}
