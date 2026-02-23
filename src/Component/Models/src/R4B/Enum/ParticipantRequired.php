<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ParticipantRequired
 * URL: http://hl7.org/fhir/ValueSet/participantrequired
 * Version: 4.3.0
 * Description: Is the Participant required to attend the appointment.
 */
enum ParticipantRequired: string
{
	/** Required */
	case required = 'required';

	/** Optional */
	case optional = 'optional';

	/** Information Only */
	case informationonly = 'information-only';
}
