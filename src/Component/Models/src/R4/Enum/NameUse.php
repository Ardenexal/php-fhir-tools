<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: NameUse
 * URL: http://hl7.org/fhir/ValueSet/name-use
 * Version: 4.0.1
 * Description: The use of a human name.
 */
enum NameUse: string
{
    /** Usual */
    case usual = 'usual';

    /** Official */
    case official = 'official';

    /** Temp */
    case temp = 'temp';

    /** Nickname */
    case nickname = 'nickname';

    /** Anonymous */
    case anonymous = 'anonymous';

    /** Old */
    case old = 'old';
}
