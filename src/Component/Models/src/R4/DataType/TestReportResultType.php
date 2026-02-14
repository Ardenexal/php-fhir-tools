<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\TestReportResult;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type TestReportResult
 *
 * @description Code type wrapper for TestReportResult enum
 */
class TestReportResultType extends CodePrimitive
{
    public function __construct(
        /** @param TestReportResult|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
