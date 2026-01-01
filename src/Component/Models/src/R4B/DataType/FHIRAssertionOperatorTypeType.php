<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRAssertionOperatorType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAssertionOperatorType
 *
 * @description Code type wrapper for FHIRAssertionOperatorType enum
 */
class FHIRAssertionOperatorTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRAssertionOperatorType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
