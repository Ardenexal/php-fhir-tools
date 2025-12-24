<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRTestReportActionResult;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRTestReportActionResult
 *
 * @description Code type wrapper for FHIRTestReportActionResult enum
 */
class FHIRFHIRTestReportActionResultType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTestReportActionResult|string|null $value The code value */
        public FHIRFHIRTestReportActionResult|string|null $value = null,
    ) {
    }
}
