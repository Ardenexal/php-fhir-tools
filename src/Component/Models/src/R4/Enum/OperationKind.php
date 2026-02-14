<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: OperationKind
 * URL: http://hl7.org/fhir/ValueSet/operation-kind
 * Version: 4.0.1
 * Description: Whether an operation is a normal operation or a query.
 */
enum OperationKind: string
{
    /** Operation */
    case operation = 'operation';

    /** Query */
    case query = 'query';
}
