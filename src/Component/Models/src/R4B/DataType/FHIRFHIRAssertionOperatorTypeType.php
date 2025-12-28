<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAssertionOperatorType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAssertionOperatorType
 *
 * @description Code type wrapper for FHIRAssertionOperatorType enum
 */
class FHIRFHIRAssertionOperatorTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAssertionOperatorType|string|null $value The code value */
        public FHIRFHIRAssertionOperatorType|string|null $value = null,
    ) {
    }
}
