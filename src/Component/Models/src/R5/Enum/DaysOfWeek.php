<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Days Of Week
 * URL: http://hl7.org/fhir/ValueSet/days-of-week
 * Version: 5.0.0
 * Description: The days of the week.
 */
enum DaysOfWeek: string
{
    /** Monday */
    case monday = 'mon';

    /** Tuesday */
    case tuesday = 'tue';

    /** Wednesday */
    case wednesday = 'wed';

    /** Thursday */
    case thursday = 'thu';

    /** Friday */
    case friday = 'fri';

    /** Saturday */
    case saturday = 'sat';

    /** Sunday */
    case sunday = 'sun';
}
