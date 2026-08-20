<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: NoteType
 * URL: http://hl7.org/fhir/ValueSet/note-type
 * Version: 4.0.1
 * Description: The presentation types of notes.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/note-type', version: '4.0.1')]
enum NoteType: string
{
    /** Display */
    case display = 'display';

    /** Print (Form) */
    case printform = 'print';

    /** Print (Operator) */
    case printoperator = 'printoper';
}
