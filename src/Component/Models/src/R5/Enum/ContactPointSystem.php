<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ContactPointSystem
 * URL: http://hl7.org/fhir/ValueSet/contact-point-system
 * Version: 5.0.0
 * Description: Telecommunications form for contact point.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/contact-point-system', version: '5.0.0')]
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
