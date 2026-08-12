<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Event Capability Mode
 * URL: http://hl7.org/fhir/ValueSet/event-capability-mode
 * Version: 5.0.0
 * Description: The mode of a message capability statement.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/event-capability-mode', version: '5.0.0')]
enum EventCapabilityMode: string
{
    /** Sender */
    case sender = 'sender';

    /** Receiver */
    case receiver = 'receiver';
}
