<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Document Mode
 * URL: http://hl7.org/fhir/ValueSet/document-mode
 * Version: 5.0.0
 * Description: Whether the application produces or consumes documents.
 */
enum DocumentMode: string
{
    /** Producer */
    case producer = 'producer';

    /** Consumer */
    case consumer = 'consumer';
}
