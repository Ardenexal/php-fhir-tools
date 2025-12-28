<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRMimeTypes;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRMimeTypes
 *
 * @description Code type wrapper for FHIRMimeTypes enum
 */
class FHIRMimeTypesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRMimeTypes|string|null $value The code value */
        public FHIRMimeTypes|string|null $value = null,
    ) {
    }
}
