<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: VariableType
 * URL: http://hl7.org/fhir/ValueSet/variable-type
 * Version: 4.3.0
 * Description: The possible types of variables for exposures or outcomes (E.g. Dichotomous, Continuous, Descriptive).
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/variable-type', version: '4.3.0')]
enum VariableType: string
{
    /** Dichotomous */
    case dichotomous = 'dichotomous';

    /** Continuous */
    case continuous = 'continuous';

    /** Descriptive */
    case descriptive = 'descriptive';
}
