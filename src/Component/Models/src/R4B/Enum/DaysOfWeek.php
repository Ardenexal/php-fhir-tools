<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: DaysOfWeek
 * URL: http://hl7.org/fhir/ValueSet/days-of-week
 * Version: 4.3.0
 * Description: The days of the week.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/days-of-week', version: '4.3.0')]
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
