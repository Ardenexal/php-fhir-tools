<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFamilyHistoryStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRFamilyHistoryStatus
 *
 * @description Code type wrapper for FHIRFamilyHistoryStatus enum
 */
class FHIRFamilyHistoryStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFamilyHistoryStatus|string|null $value The code value */
        public FHIRFamilyHistoryStatus|string|null $value = null,
    ) {
    }
}
