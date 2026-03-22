<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\SubscriptionChannelType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type SubscriptionChannelType
 *
 * @description Code type wrapper for SubscriptionChannelType enum
 */
class SubscriptionChannelTypeType extends CodePrimitive
{
    public function __construct(
        /** @param SubscriptionChannelType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
