<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: CompositionStatus
 * URL: http://hl7.org/fhir/ValueSet/composition-status
 * Version: 4.3.0
 * Description: The workflow/clinical status of the composition.
 */
enum FHIRCompositionStatus: string
{
	/** Preliminary */
	case preliminary = 'preliminary';

	/** Final */
	case final = 'final';

	/** Amended */
	case amended = 'amended';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';
}
