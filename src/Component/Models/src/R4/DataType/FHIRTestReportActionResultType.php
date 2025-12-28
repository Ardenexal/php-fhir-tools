<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestReportActionResult;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTestReportActionResult
 *
 * @description Code type wrapper for FHIRTestReportActionResult enum
 */
class FHIRTestReportActionResultType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTestReportActionResult|string|null $value The code value */
        public FHIRTestReportActionResult|string|null $value = null,
    ) {
    }
}
