<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRMimeTypes;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRMimeTypes
 *
 * @description Code type wrapper for FHIRMimeTypes enum
 */
class FHIRFHIRMimeTypesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRMimeTypes|string|null $value The code value */
        public FHIRFHIRMimeTypes|string|null $value = null,
    ) {
    }
}
