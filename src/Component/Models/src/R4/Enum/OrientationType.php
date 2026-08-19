<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: orientationType
 * URL: http://hl7.org/fhir/ValueSet/orientation-type
 * Version: 4.0.1
 * Description: Type for orientation.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/orientation-type', version: '4.0.1')]
enum OrientationType: string
{
    /** Sense orientation of referenceSeq */
    case senseorientationofreferenceseq = 'sense';

    /** Antisense orientation of referenceSeq */
    case antisenseorientationofreferenceseq = 'antisense';
}
