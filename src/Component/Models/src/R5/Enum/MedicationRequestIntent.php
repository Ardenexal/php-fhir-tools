<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: medicationRequest Intent
 * URL: http://hl7.org/fhir/ValueSet/medicationrequest-intent
 * Version: 5.0.0
 * Description: MedicationRequest Intent Codes
 */
enum MedicationRequestIntent: string
{
	/** Proposal */
	case proposal = 'proposal';

	/** Plan */
	case plan = 'plan';

	/** Order */
	case order = 'order';

	/** Option */
	case option = 'option';
}
