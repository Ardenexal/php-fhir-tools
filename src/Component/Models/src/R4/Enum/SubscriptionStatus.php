<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SubscriptionStatus
 * URL: http://hl7.org/fhir/ValueSet/subscription-status
 * Version: 4.0.1
 * Description: The status of a subscription.
 */
enum SubscriptionStatus: string
{
    /** Requested */
    case requested = 'requested';

    /** Active */
    case active = 'active';

    /** Error */
    case error = 'error';

    /** Off */
    case off = 'off';
}
