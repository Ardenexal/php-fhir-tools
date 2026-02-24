<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\SubscriptionNotificationType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type SubscriptionNotificationType
 *
 * @description Code type wrapper for SubscriptionNotificationType enum
 */
class SubscriptionNotificationTypeType extends CodePrimitive
{
    public function __construct(
        /** @param SubscriptionNotificationType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
