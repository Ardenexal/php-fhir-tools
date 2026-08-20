<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Operation Parameter Scope
 * URL: http://hl7.org/fhir/ValueSet/operation-parameter-scope
 * Version: 5.0.0
 * Description: Indicates that a parameter applies when the operation is being invoked at the specified level
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/operation-parameter-scope', version: '5.0.0')]
enum OperationParameterScope: string
{
    /** Instance */
    case instance = 'instance';

    /** Type */
    case type = 'type';

    /** System */
    case system = 'system';
}
