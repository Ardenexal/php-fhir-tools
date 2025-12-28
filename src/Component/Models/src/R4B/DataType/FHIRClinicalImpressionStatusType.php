<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRClinicalImpressionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRClinicalImpressionStatus
 *
 * @description Code type wrapper for FHIRClinicalImpressionStatus enum
 */
class FHIRClinicalImpressionStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRClinicalImpressionStatus|string|null $value The code value */
        public FHIRClinicalImpressionStatus|string|null $value = null,
    ) {
    }
}
