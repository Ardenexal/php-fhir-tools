<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Vision Eyes
 * URL: http://hl7.org/fhir/ValueSet/vision-eye-codes
 * Version: 5.0.0
 * Description: A coded concept listing the eye codes.
 */
enum VisionEyes: string
{
    /** Right Eye */
    case righteye = 'right';

    /** Left Eye */
    case lefteye = 'left';
}
