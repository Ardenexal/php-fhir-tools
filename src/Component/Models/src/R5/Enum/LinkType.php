<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Link Type
 * URL: http://hl7.org/fhir/ValueSet/link-type
 * Version: 5.0.0
 * Description: The type of link between this Patient resource and another Patient/RelatedPerson resource.
 */
enum LinkType: string
{
	/** Replaced-by */
	case replacedby = 'replaced-by';

	/** Replaces */
	case replaces = 'replaces';

	/** Refer */
	case refer = 'refer';

	/** See also */
	case seealso = 'seealso';
}
