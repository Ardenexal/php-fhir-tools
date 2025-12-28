<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTestReportResult;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTestReportResult
 *
 * @description Code type wrapper for FHIRTestReportResult enum
 */
class FHIRFHIRTestReportResultType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTestReportResult|string|null $value The code value */
        public FHIRFHIRTestReportResult|string|null $value = null,
    ) {
    }
}
