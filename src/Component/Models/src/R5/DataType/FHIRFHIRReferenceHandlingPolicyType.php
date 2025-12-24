<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRReferenceHandlingPolicy;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRReferenceHandlingPolicy
 *
 * @description Code type wrapper for FHIRReferenceHandlingPolicy enum
 */
class FHIRFHIRReferenceHandlingPolicyType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRReferenceHandlingPolicy|string|null $value The code value */
        public FHIRFHIRReferenceHandlingPolicy|string|null $value = null,
    ) {
    }
}
