<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: AuditEventOutcome
 * URL: http://hl7.org/fhir/ValueSet/audit-event-outcome
 * Version: 4.3.0
 * Description: Indicates whether the event succeeded or failed.
 */
enum FHIRAuditEventOutcome: string
{
	/** Success */
	case success = '0';

	/** Minor failure */
	case minorfailure = '4';

	/** Serious failure */
	case seriousfailure = '8';

	/** Major failure */
	case majorfailure = '12';
}
