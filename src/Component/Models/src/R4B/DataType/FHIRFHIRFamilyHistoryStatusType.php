<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRFamilyHistoryStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRFamilyHistoryStatus
 *
 * @description Code type wrapper for FHIRFamilyHistoryStatus enum
 */
class FHIRFHIRFamilyHistoryStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRFamilyHistoryStatus|string|null $value The code value */
        public FHIRFHIRFamilyHistoryStatus|string|null $value = null,
    ) {
    }
}
