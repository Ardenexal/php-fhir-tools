<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: OperationParameterUse
 * URL: http://hl7.org/fhir/ValueSet/operation-parameter-use
 * Version: 4.0.1
 * Description: Whether an operation parameter is an input or an output parameter.
 */
enum OperationParameterUse: string
{
    /** In */
    case in = 'in';

    /** Out */
    case out = 'out';
}
