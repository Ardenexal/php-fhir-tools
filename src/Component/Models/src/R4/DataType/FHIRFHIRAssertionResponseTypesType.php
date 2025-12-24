<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRAssertionResponseTypes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRAssertionResponseTypes
 *
 * @description Code type wrapper for FHIRAssertionResponseTypes enum
 */
class FHIRFHIRAssertionResponseTypesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRAssertionResponseTypes|string|null $value The code value */
        public FHIRFHIRAssertionResponseTypes|string|null $value = null,
    ) {
    }
}
