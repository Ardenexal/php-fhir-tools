<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: DocumentReferenceStatus
 * URL: http://hl7.org/fhir/ValueSet/document-reference-status
 * Version: 4.3.0
 * Description: The status of the document reference.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/document-reference-status', version: '4.3.0')]
enum DocumentReferenceStatus: string
{
    /** Current */
    case current = 'current';

    /** Superseded */
    case superseded = 'superseded';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
