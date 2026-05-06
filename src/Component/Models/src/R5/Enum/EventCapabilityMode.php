<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Event Capability Mode
 * URL: http://hl7.org/fhir/ValueSet/event-capability-mode
 * Version: 5.0.0
 * Description: The mode of a message capability statement.
 */
enum EventCapabilityMode: string
{
    /** Sender */
    case sender = 'sender';

    /** Receiver */
    case receiver = 'receiver';
}
