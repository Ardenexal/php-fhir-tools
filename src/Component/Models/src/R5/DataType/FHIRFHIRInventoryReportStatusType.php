<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRInventoryReportStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInventoryReportStatus
 *
 * @description Code type wrapper for FHIRInventoryReportStatus enum
 */
class FHIRFHIRInventoryReportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRInventoryReportStatus|string|null $value The code value */
        public FHIRFHIRInventoryReportStatus|string|null $value = null,
    ) {
    }
}
