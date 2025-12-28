<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: ConceptMap Attribute Type
 * URL: http://hl7.org/fhir/ValueSet/conceptmap-attribute-type
 * Version: 5.0.0
 * Description: The type of a ConceptMap mapping attribute value.
 */
enum FHIRConceptMapAttributeType: string
{
	/** code */
	case code = 'code';

	/** Coding */
	case coding = 'Coding';

	/** string */
	case string = 'string';

	/** boolean */
	case boolean = 'boolean';

	/** Quantity */
	case quantity = 'Quantity';
}
