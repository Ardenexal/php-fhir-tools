<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRAssertionManualCompletionType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAssertionManualCompletionType
 *
 * @description Code type wrapper for FHIRAssertionManualCompletionType enum
 */
class FHIRFHIRAssertionManualCompletionTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAssertionManualCompletionType|string|null $value The code value */
        public FHIRFHIRAssertionManualCompletionType|string|null $value = null,
    ) {
    }
}
