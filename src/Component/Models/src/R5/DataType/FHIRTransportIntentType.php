<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRTransportIntent;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRTransportIntent
 *
 * @description Code type wrapper for FHIRTransportIntent enum
 */
class FHIRTransportIntentType extends FHIRCode
{
    public function __construct(
        /** @var FHIRTransportIntent|string|null $value The code value */
        public FHIRTransportIntent|string|null $value = null,
    ) {
    }
}
