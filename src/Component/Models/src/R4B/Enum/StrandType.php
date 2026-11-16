<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: strandType
 * URL: http://hl7.org/fhir/ValueSet/strand-type
 * Version: 4.3.0
 * Description: Type for strand.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/strand-type', version: '4.3.0')]
enum StrandType: string
{
    /** Watson strand of referenceSeq */
    case watsonstrandofreferenceseq = 'watson';

    /** Crick strand of referenceSeq */
    case crickstrandofreferenceseq = 'crick';
}
