<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: SubscriptionChannelType
 * URL: http://hl7.org/fhir/ValueSet/subscription-channel-type
 * Version: 4.0.1
 * Description: The type of method used to execute a subscription.
 */
enum SubscriptionChannelType: string
{
    /** Rest Hook */
    case resthook = 'rest-hook';

    /** Websocket */
    case websocket = 'websocket';

    /** Email */
    case email = 'email';

    /** SMS */
    case sms = 'sms';

    /** Message */
    case message = 'message';
}
