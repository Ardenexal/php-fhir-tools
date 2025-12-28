<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMeasureReportType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMeasureReportType
 *
 * @description Code type wrapper for FHIRMeasureReportType enum
 */
class FHIRFHIRMeasureReportTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMeasureReportType|string|null $value The code value */
        public FHIRFHIRMeasureReportType|string|null $value = null,
    ) {
    }
}
