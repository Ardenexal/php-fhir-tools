<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: MessageSignificanceCategory
 * URL: http://hl7.org/fhir/ValueSet/message-significance-category
 * Version: 4.0.1
 * Description: The impact of the content of a message.
 */
enum MessageSignificanceCategory: string
{
    /** Consequence */
    case consequence = 'consequence';

    /** Currency */
    case currency = 'currency';

    /** Notification */
    case notification = 'notification';
}
