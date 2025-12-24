<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRSubscriptionNotificationType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRSubscriptionNotificationType
 *
 * @description Code type wrapper for FHIRSubscriptionNotificationType enum
 */
class FHIRFHIRSubscriptionNotificationTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSubscriptionNotificationType|string|null $value The code value */
        public FHIRFHIRSubscriptionNotificationType|string|null $value = null,
    ) {
    }
}
