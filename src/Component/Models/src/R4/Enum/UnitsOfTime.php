<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: UnitsOfTime
 * URL: http://hl7.org/fhir/ValueSet/units-of-time
 * Version: 4.0.1
 * Description: A unit of time (units from UCUM).
 */
enum UnitsOfTime: string
{
    /** second */
    case second = 's';

    /** minute */
    case minute = 'min';

    /** hour */
    case hour = 'h';

    /** day */
    case day = 'd';

    /** week */
    case week = 'wk';

    /** month */
    case month = 'mo';

    /** year */
    case year = 'a';
}
