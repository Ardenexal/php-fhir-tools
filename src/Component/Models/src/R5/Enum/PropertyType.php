<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Property Type
 * URL: http://hl7.org/fhir/ValueSet/concept-property-type
 * Version: 5.0.0
 * Description: The type of a property value.
 */
enum PropertyType: string
{
	/** code (internal reference) */
	case codeinternalreference = 'code';

	/** Coding (external reference) */
	case codingexternalreference = 'Coding';

	/** string */
	case string = 'string';

	/** integer */
	case integer = 'integer';

	/** boolean */
	case boolean = 'boolean';

	/** dateTime */
	case datetime = 'dateTime';

	/** decimal */
	case decimal = 'decimal';
}
