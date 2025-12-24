<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAssertionDirectionType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAssertionDirectionType
 *
 * @description Code type wrapper for FHIRAssertionDirectionType enum
 */
class FHIRFHIRAssertionDirectionTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAssertionDirectionType|string|null $value The code value */
        public FHIRFHIRAssertionDirectionType|string|null $value = null,
    ) {
    }
}
