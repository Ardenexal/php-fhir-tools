<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: MessageSignificanceCategory
 * URL: http://hl7.org/fhir/ValueSet/message-significance-category
 * Version: 4.0.1
 * Description: The impact of the content of a message.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/message-significance-category', version: '4.0.1')]
enum MessageSignificanceCategory: string
{
    /** Consequence */
    case consequence = 'consequence';

    /** Currency */
    case currency = 'currency';

    /** Notification */
    case notification = 'notification';
}
