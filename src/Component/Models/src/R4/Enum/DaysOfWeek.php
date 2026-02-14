<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DaysOfWeek
 * URL: http://hl7.org/fhir/ValueSet/days-of-week
 * Version: 4.0.1
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
