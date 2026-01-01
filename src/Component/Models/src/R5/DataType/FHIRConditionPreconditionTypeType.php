<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRConditionPreconditionType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRConditionPreconditionType
 *
 * @description Code type wrapper for FHIRConditionPreconditionType enum
 */
class FHIRConditionPreconditionTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRConditionPreconditionType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
