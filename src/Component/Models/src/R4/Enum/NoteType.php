<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: NoteType
 * URL: http://hl7.org/fhir/ValueSet/note-type
 * Version: 4.0.1
 * Description: The presentation types of notes.
 */
enum NoteType: string
{
    /** Display */
    case display = 'display';

    /** Print (Form) */
    case printform = 'print';

    /** Print (Operator) */
    case printoperator = 'printoper';
}
