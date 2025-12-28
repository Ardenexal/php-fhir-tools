<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRAssertionManualCompletionType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRAssertionManualCompletionType
 *
 * @description Code type wrapper for FHIRAssertionManualCompletionType enum
 */
class FHIRAssertionManualCompletionTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRAssertionManualCompletionType|string|null $value The code value */
        public FHIRAssertionManualCompletionType|string|null $value = null,
    ) {
    }
}
