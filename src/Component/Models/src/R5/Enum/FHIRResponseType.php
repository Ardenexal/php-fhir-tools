<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: ResponseType
 * URL: http://hl7.org/fhir/ValueSet/response-code
 * Version: 4.0.1
 * Description: The kind of response to a message.
 */
enum FHIRResponseType: string
{
	/** OK */
	case ok = 'ok';

	/** Transient Error */
	case transienterror = 'transient-error';

	/** Fatal Error */
	case fatalerror = 'fatal-error';
}
