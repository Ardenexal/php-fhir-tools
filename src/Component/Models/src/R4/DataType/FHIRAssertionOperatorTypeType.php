<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

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
        /** @var FHIRAssertionOperatorType|string|null $value The code value */
        public FHIRAssertionOperatorType|string|null $value = null,
    ) {
    }
}
