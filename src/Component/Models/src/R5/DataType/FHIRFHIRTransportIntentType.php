<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRTransportIntent;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRTransportIntent
 *
 * @description Code type wrapper for FHIRTransportIntent enum
 */
class FHIRFHIRTransportIntentType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRTransportIntent|string|null $value The code value */
        public FHIRFHIRTransportIntent|string|null $value = null,
    ) {
    }
}
