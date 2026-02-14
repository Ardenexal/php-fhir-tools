<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ObservationDataType
 * URL: http://hl7.org/fhir/ValueSet/permitted-data-type
 * Version: 4.0.1
 * Description: Permitted data type for observation value.
 */
enum ObservationDataType: string
{
    /** Quantity */
    case quantity = 'Quantity';

    /** CodeableConcept */
    case codeableconcept = 'CodeableConcept';

    /** string */
    case string = 'string';

    /** boolean */
    case boolean = 'boolean';

    /** integer */
    case integer = 'integer';

    /** Range */
    case range = 'Range';

    /** Ratio */
    case ratio = 'Ratio';

    /** SampledData */
    case sampleddata = 'SampledData';

    /** time */
    case time = 'time';

    /** dateTime */
    case datetime = 'dateTime';

    /** Period */
    case period = 'Period';
}
