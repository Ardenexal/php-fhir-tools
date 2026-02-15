<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DocumentReferenceStatus
 * URL: http://hl7.org/fhir/ValueSet/document-reference-status
 * Version: 4.0.1
 * Description: The status of the document reference.
 */
enum DocumentReferenceStatus: string
{
    /** Current */
    case current = 'current';

    /** Superseded */
    case superseded = 'superseded';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';
}
