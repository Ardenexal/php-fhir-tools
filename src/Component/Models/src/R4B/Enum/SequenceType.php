<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: sequenceType
 * URL: http://hl7.org/fhir/ValueSet/sequence-type
 * Version: 4.3.0
 * Description: Type if a sequence -- DNA, RNA, or amino acid sequence.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/sequence-type', version: '4.3.0')]
enum SequenceType: string
{
    /** AA Sequence */
    case aasequence = 'aa';

    /** DNA Sequence */
    case dnasequence = 'dna';

    /** RNA Sequence */
    case rnasequence = 'rna';
}
