<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Characteristic Combination
 * URL: http://hl7.org/fhir/ValueSet/characteristic-combination
 * Version: 5.0.0
 * Description: Logical grouping of characteristics.
 */
enum CharacteristicCombination: string
{
	/** All of */
	case allof = 'all-of';

	/** Any of */
	case anyof = 'any-of';

	/** At least */
	case atleast = 'at-least';

	/** At most */
	case atmost = 'at-most';

	/** Statistical */
	case statistical = 'statistical';

	/** Net effect */
	case neteffect = 'net-effect';

	/** Dataset */
	case dataset = 'dataset';
}
