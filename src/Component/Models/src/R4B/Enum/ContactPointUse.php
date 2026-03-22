<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ContactPointUse
 * URL: http://hl7.org/fhir/ValueSet/contact-point-use
 * Version: 4.3.0
 * Description: Use of contact point.
 */
enum ContactPointUse: string
{
    /** Home */
    case home = 'home';

    /** Work */
    case work = 'work';

    /** Temp */
    case temp = 'temp';

    /** Old */
    case old = 'old';

    /** Mobile */
    case mobile = 'mobile';
}
