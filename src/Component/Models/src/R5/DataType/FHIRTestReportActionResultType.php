<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIRTestReportActionResult|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
