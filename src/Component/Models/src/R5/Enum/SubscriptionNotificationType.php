<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Subscription Notification Type
 * URL: http://hl7.org/fhir/ValueSet/subscription-notification-type
 * Version: 5.0.0
 * Description: The type of notification represented by the status message.
 */
enum SubscriptionNotificationType: string
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
