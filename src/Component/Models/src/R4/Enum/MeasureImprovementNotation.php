<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: MeasureImprovementNotation
 * URL: http://hl7.org/fhir/ValueSet/measure-improvement-notation
 * Version: 4.0.1
 * Description: Observation values that indicate what change in a measurement value or score is indicative of an improvement in the measured item or scored issue.
 */
enum MeasureImprovementNotation: string
{
    /** Increased score indicates improvement */
    case increasedscoreindicatesimprovement = 'increase';

    /** Decreased score indicates improvement */
    case decreasedscoreindicatesimprovement = 'decrease';
}
