<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: VisionBase
 * URL: http://hl7.org/fhir/ValueSet/vision-base-codes
 * Version: 4.3.0
 * Description: A coded concept listing the base codes.
 */
enum VisionBase: string
{
	/** Up */
	case up = 'up';

	/** Down */
	case down = 'down';

	/** In */
	case in = 'in';

	/** Out */
	case out = 'out';
}
