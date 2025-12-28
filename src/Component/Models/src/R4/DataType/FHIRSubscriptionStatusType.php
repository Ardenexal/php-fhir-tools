<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSubscriptionStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionStatus
 *
 * @description Code type wrapper for FHIRSubscriptionStatus enum
 */
class FHIRSubscriptionStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSubscriptionStatus|string|null $value The code value */
        public FHIRSubscriptionStatus|string|null $value = null,
    ) {
    }
}
