<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSubscriptionChannelType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionChannelType
 *
 * @description Code type wrapper for FHIRSubscriptionChannelType enum
 */
class FHIRSubscriptionChannelTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRSubscriptionChannelType|string|null $value The code value */
        public FHIRSubscriptionChannelType|string|null $value = null,
    ) {
    }
}
