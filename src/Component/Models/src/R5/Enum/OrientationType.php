<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: orientation Type
 * URL: http://hl7.org/fhir/ValueSet/orientation-type
 * Version: 5.0.0
 * Description: Type for orientation.
 */
enum OrientationType: string
{
    /** Sense orientation of referenceSeq */
    case senseorientationofreferenceseq = 'sense';

    /** Antisense orientation of referenceSeq */
    case antisenseorientationofreferenceseq = 'antisense';
}
