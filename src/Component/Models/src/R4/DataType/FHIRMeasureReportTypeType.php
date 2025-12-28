<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMeasureReportType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMeasureReportType
 *
 * @description Code type wrapper for FHIRMeasureReportType enum
 */
class FHIRMeasureReportTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMeasureReportType|string|null $value The code value */
        public FHIRMeasureReportType|string|null $value = null,
    ) {
    }
}
