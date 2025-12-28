<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @var FHIRSubscriptionNotificationType|string|null $value The code value */
        public FHIRSubscriptionNotificationType|string|null $value = null,
    ) {
    }
}
