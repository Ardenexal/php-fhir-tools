<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\MeasureReportStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type MeasureReportStatus
 *
 * @description Code type wrapper for MeasureReportStatus enum
 */
class MeasureReportStatusType extends CodePrimitive
{
    public function __construct(
        /** @param MeasureReportStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
