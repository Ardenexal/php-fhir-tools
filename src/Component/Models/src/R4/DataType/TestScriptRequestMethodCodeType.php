<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\TestScriptRequestMethodCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type TestScriptRequestMethodCode
 *
 * @description Code type wrapper for TestScriptRequestMethodCode enum
 */
class TestScriptRequestMethodCodeType extends CodePrimitive
{
    public function __construct(
        /** @param TestScriptRequestMethodCode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
