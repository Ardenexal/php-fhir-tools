<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRHTTPVerb;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRHTTPVerb
 *
 * @description Code type wrapper for FHIRHTTPVerb enum
 */
class FHIRFHIRHTTPVerbType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRHTTPVerb|string|null $value The code value */
        public FHIRFHIRHTTPVerb|string|null $value = null,
    ) {
    }
}
