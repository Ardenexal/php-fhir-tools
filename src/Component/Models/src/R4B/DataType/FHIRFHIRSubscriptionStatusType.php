<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSubscriptionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionStatus
 *
 * @description Code type wrapper for FHIRSubscriptionStatus enum
 */
class FHIRFHIRSubscriptionStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSubscriptionStatus|string|null $value The code value */
        public FHIRFHIRSubscriptionStatus|string|null $value = null,
    ) {
    }
}
