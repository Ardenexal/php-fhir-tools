<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SubscriptionStatusCodes
 * URL: http://hl7.org/fhir/ValueSet/subscription-status
 * Version: 4.3.0
 * Description: The status of a subscription.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/subscription-status', version: '4.3.0')]
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
}
