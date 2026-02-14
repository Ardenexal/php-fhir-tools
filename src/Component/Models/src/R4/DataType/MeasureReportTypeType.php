<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\MeasureReportType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type MeasureReportType
 *
 * @description Code type wrapper for MeasureReportType enum
 */
class MeasureReportTypeType extends CodePrimitive
{
    public function __construct(
        /** @param MeasureReportType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
