<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: OperationKind
 * URL: http://hl7.org/fhir/ValueSet/operation-kind
 * Version: 4.3.0
 * Description: Whether an operation is a normal operation or a query.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/operation-kind', version: '4.3.0')]
enum OperationKind: string
{
    /** Operation */
    case operation = 'operation';

    /** Query */
    case query = 'query';
}
