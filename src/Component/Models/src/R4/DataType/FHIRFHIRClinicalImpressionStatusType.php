<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRClinicalImpressionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRClinicalImpressionStatus
 *
 * @description Code type wrapper for FHIRClinicalImpressionStatus enum
 */
class FHIRFHIRClinicalImpressionStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRClinicalImpressionStatus|string|null $value The code value */
        public FHIRFHIRClinicalImpressionStatus|string|null $value = null,
    ) {
    }
}
