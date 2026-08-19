<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Operation Parameter Use
 * URL: http://hl7.org/fhir/ValueSet/operation-parameter-use
 * Version: 5.0.0
 * Description: Whether an operation parameter is an input or an output parameter.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/operation-parameter-use', version: '5.0.0')]
enum OperationParameterUse: string
{
    /** In */
    case in = 'in';

    /** Out */
    case out = 'out';
}
