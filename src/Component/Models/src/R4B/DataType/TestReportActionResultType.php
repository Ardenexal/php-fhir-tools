<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\TestReportActionResult;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type TestReportActionResult
 *
 * @description Code type wrapper for TestReportActionResult enum
 */
class TestReportActionResultType extends CodePrimitive
{
    public function __construct(
        /** @param TestReportActionResult|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
