<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRSubscriptionNotificationType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionNotificationType
 *
 * @description Code type wrapper for FHIRSubscriptionNotificationType enum
 */
class FHIRSubscriptionNotificationTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRSubscriptionNotificationType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
