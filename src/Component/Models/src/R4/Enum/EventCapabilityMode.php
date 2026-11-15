<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: EventCapabilityMode
 * URL: http://hl7.org/fhir/ValueSet/event-capability-mode
 * Version: 4.0.1
 * Description: The mode of a message capability statement.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/event-capability-mode', version: '4.0.1')]
enum EventCapabilityMode: string
{
    /** Sender */
    case sender = 'sender';

    /** Receiver */
    case receiver = 'receiver';
}
