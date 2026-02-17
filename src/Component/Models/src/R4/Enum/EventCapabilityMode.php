<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: EventCapabilityMode
 * URL: http://hl7.org/fhir/ValueSet/event-capability-mode
 * Version: 4.0.1
 * Description: The mode of a message capability statement.
 */
enum EventCapabilityMode: string
{
    /** Sender */
    case sender = 'sender';

    /** Receiver */
    case receiver = 'receiver';
}
