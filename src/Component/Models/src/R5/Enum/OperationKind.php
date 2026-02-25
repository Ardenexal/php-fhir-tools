<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Operation Kind
 * URL: http://hl7.org/fhir/ValueSet/operation-kind
 * Version: 5.0.0
 * Description: Whether an operation is a normal operation or a query.
 */
enum OperationKind: string
{
	/** Operation */
	case operation = 'operation';

	/** Query */
	case query = 'query';
}
