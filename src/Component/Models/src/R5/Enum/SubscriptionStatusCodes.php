<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Subscription Status
 * URL: http://hl7.org/fhir/ValueSet/subscription-status
 * Version: 5.0.0
 * Description: State values for FHIR Subscriptions.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/subscription-status', version: '5.0.0')]
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
