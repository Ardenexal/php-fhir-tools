<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Subscription Status
 * URL: http://hl7.org/fhir/ValueSet/subscription-status
 * Version: 5.0.0
 * Description: State values for FHIR Subscriptions.
 */
enum SubscriptionStatusCodes: string
{
    /** Requested */
    case requested = 'requested';

    /** Active */
    case active = 'active';

    /** Error */
    case error = 'error';

    /** Off */
    case off = 'off';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
