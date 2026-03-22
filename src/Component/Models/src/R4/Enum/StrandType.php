<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: strandType
 * URL: http://hl7.org/fhir/ValueSet/strand-type
 * Version: 4.0.1
 * Description: Type for strand.
 */
enum StrandType: string
{
    /** Watson strand of referenceSeq */
    case watsonstrandofreferenceseq = 'watson';

    /** Crick strand of referenceSeq */
    case crickstrandofreferenceseq = 'crick';
}
