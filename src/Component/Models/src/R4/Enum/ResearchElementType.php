<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ResearchElementType
 * URL: http://hl7.org/fhir/ValueSet/research-element-type
 * Version: 4.0.1
 * Description: The possible types of research elements (E.g. Population, Exposure, Outcome).
 */
enum ResearchElementType: string
{
    /** Population */
    case population = 'population';

    /** Exposure */
    case exposure = 'exposure';

    /** Outcome */
    case outcome = 'outcome';
}
