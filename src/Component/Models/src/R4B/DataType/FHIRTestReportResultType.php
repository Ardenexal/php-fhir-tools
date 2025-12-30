<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRTestReportResult;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTestReportResult
 *
 * @description Code type wrapper for FHIRTestReportResult enum
 */
class FHIRTestReportResultType extends FHIRCode
{
    public function __construct(
        /** @param FHIRTestReportResult|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
