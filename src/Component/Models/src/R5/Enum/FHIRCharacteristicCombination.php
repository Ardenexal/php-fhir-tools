<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: CharacteristicCombination
 * URL: http://hl7.org/fhir/ValueSet/characteristic-combination
 * Version: 4.3.0
 * Description: Logical grouping of characteristics.
 */
enum FHIRCharacteristicCombination: string
{
	/** intersection */
	case intersection = 'intersection';

	/** union */
	case union = 'union';
}
