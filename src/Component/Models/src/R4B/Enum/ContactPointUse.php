<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ContactPointUse
 * URL: http://hl7.org/fhir/ValueSet/contact-point-use
 * Version: 4.3.0
 * Description: Use of contact point.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/contact-point-use', version: '4.3.0')]
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
