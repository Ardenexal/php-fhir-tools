<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Structure Map Transform
 * URL: http://hl7.org/fhir/ValueSet/map-transform
 * Version: 5.0.0
 * Description: How data is copied/created.
 */
enum StructureMapTransform: string
{
	/** create */
	case create = 'create';

	/** copy */
	case copy = 'copy';

	/** truncate */
	case truncate = 'truncate';

	/** escape */
	case escape = 'escape';

	/** cast */
	case cast = 'cast';

	/** append */
	case append = 'append';

	/** translate */
	case translate = 'translate';

	/** reference */
	case reference = 'reference';

	/** dateOp */
	case dateop = 'dateOp';

	/** uuid */
	case uuid = 'uuid';

	/** pointer */
	case pointer = 'pointer';

	/** evaluate */
	case evaluate = 'evaluate';

	/** cc */
	case cc = 'cc';

	/** c */
	case c = 'c';

	/** qty */
	case qty = 'qty';

	/** id */
	case id = 'id';

	/** cp */
	case cp = 'cp';
}
