<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DeviceMetricColor
 * URL: http://hl7.org/fhir/ValueSet/metric-color
 * Version: 4.0.1
 * Description: Describes the typical color of representation.
 */
enum DeviceMetricColor: string
{
    /** Color Black */
    case colorblack = 'black';

    /** Color Red */
    case colorred = 'red';

    /** Color Green */
    case colorgreen = 'green';

    /** Color Yellow */
    case coloryellow = 'yellow';

    /** Color Blue */
    case colorblue = 'blue';

    /** Color Magenta */
    case colormagenta = 'magenta';

    /** Color Cyan */
    case colorcyan = 'cyan';

    /** Color White */
    case colorwhite = 'white';
}
