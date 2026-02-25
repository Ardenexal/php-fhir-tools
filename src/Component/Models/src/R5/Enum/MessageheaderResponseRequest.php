<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: messageheader-response-request
 * URL: http://hl7.org/fhir/ValueSet/messageheader-response-request
 * Version: 5.0.0
 * Description: HL7-defined table of codes which identify conditions under which acknowledgments are required to be returned in response to a message.
 */
enum MessageheaderResponseRequest: string
{
	/** Always */
	case always = 'always';

	/** Error/reject conditions only */
	case errorrejectconditionsonly = 'on-error';

	/** Never */
	case never = 'never';

	/** Successful completion only */
	case successfulcompletiononly = 'on-success';
}
