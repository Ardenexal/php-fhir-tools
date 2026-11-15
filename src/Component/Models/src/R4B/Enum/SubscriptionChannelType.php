<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: SubscriptionChannelType
 * URL: http://hl7.org/fhir/ValueSet/subscription-channel-type
 * Version: 4.3.0
 * Description: The type of method used to execute a subscription.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/subscription-channel-type', version: '4.3.0')]
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
