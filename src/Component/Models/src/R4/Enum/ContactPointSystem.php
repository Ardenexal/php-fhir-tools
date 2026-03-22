<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ContactPointSystem
 * URL: http://hl7.org/fhir/ValueSet/contact-point-system
 * Version: 4.0.1
 * Description: Telecommunications form for contact point.
 */
enum ContactPointSystem: string
{
    /** Phone */
    case phone = 'phone';

    /** Fax */
    case fax = 'fax';

    /** Email */
    case email = 'email';

    /** Pager */
    case pager = 'pager';

    /** URL */
    case url = 'url';

    /** SMS */
    case sms = 'sms';

    /** Other */
    case other = 'other';
}
