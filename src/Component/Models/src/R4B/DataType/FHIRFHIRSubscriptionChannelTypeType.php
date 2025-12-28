<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSubscriptionChannelType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionChannelType
 *
 * @description Code type wrapper for FHIRSubscriptionChannelType enum
 */
class FHIRFHIRSubscriptionChannelTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSubscriptionChannelType|string|null $value The code value */
        public FHIRFHIRSubscriptionChannelType|string|null $value = null,
    ) {
    }
}
