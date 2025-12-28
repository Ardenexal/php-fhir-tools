<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: SubscriptionNotificationType
 * URL: http://hl7.org/fhir/ValueSet/subscription-notification-type
 * Version: 4.3.0
 * Description: The type of notification represented by the status message.
 */
enum FHIRSubscriptionNotificationType: string
{
    /** Handshake */
    case handshake = 'handshake';

    /** Heartbeat */
    case heartbeat = 'heartbeat';

    /** Event Notification */
    case eventnotification = 'event-notification';

    /** Query Status */
    case querystatus = 'query-status';

    /** Query Event */
    case queryevent = 'query-event';
}
