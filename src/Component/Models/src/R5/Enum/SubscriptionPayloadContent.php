<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Subscription Payload Content
 * URL: http://hl7.org/fhir/ValueSet/subscription-payload-content
 * Version: 5.0.0
 * Description: Codes to represent how much resource content to send in the notification payload.
 */
enum SubscriptionPayloadContent: string
{
    /** Empty */
    case empty = 'empty';

    /** Id-only */
    case idonly = 'id-only';

    /** Full-resource */
    case fullresource = 'full-resource';
}
