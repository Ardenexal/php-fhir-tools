<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: EvidenceVariableType
 * URL: http://hl7.org/fhir/ValueSet/variable-type
 * Version: 4.0.1
 * Description: The possible types of variables for exposures or outcomes (E.g. Dichotomous, Continuous, Descriptive).
 */
enum EvidenceVariableType: string
{
    /** Dichotomous */
    case dichotomous = 'dichotomous';

    /** Continuous */
    case continuous = 'continuous';

    /** Descriptive */
    case descriptive = 'descriptive';
}
