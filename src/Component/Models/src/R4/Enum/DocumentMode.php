<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: DocumentMode
 * URL: http://hl7.org/fhir/ValueSet/document-mode
 * Version: 4.0.1
 * Description: Whether the application produces or consumes documents.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/document-mode', version: '4.0.1')]
enum DocumentMode: string
{
    /** Producer */
    case producer = 'producer';

    /** Consumer */
    case consumer = 'consumer';
}
