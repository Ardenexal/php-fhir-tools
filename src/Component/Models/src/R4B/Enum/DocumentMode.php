<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: DocumentMode
 * URL: http://hl7.org/fhir/ValueSet/document-mode
 * Version: 4.3.0
 * Description: Whether the application produces or consumes documents.
 */
enum DocumentMode: string
{
    /** Producer */
    case producer = 'producer';

    /** Consumer */
    case consumer = 'consumer';
}
