<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: orientationType
 * URL: http://hl7.org/fhir/ValueSet/orientation-type
 * Version: 4.0.1
 * Description: Type for orientation.
 */
enum OrientationType: string
{
    /** Sense orientation of referenceSeq */
    case senseorientationofreferenceseq = 'sense';

    /** Antisense orientation of referenceSeq */
    case antisenseorientationofreferenceseq = 'antisense';
}
