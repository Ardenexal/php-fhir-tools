<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRInventoryReportStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRInventoryReportStatus
 *
 * @description Code type wrapper for FHIRInventoryReportStatus enum
 */
class FHIRInventoryReportStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRInventoryReportStatus|string|null $value The code value */
        public FHIRInventoryReportStatus|string|null $value = null,
    ) {
    }
}
