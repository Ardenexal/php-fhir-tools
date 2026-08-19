<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: orientation Type
 * URL: http://hl7.org/fhir/ValueSet/orientation-type
 * Version: 5.0.0
 * Description: Type for orientation.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/orientation-type', version: '5.0.0')]
enum OrientationType: string
{
    /** Sense orientation of referenceSeq */
    case senseorientationofreferenceseq = 'sense';

    /** Antisense orientation of referenceSeq */
    case antisenseorientationofreferenceseq = 'antisense';
}
