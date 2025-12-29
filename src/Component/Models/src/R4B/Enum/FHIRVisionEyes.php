<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: VisionEyes
 * URL: http://hl7.org/fhir/ValueSet/vision-eye-codes
 * Version: 4.3.0
 * Description: A coded concept listing the eye codes.
 */
enum FHIRVisionEyes: string
{
	/** Right Eye */
	case righteye = 'right';

	/** Left Eye */
	case lefteye = 'left';
}
