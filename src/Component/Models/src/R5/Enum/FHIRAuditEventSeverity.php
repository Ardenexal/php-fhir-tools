<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Audit Event Severity
 * URL: http://hl7.org/fhir/ValueSet/audit-event-severity
 * Version: 5.0.0
 * Description: The severity of the audit entry.
 */
enum FHIRAuditEventSeverity: string
{
	/** Emergency */
	case emergency = 'emergency';

	/** Alert */
	case alert = 'alert';

	/** Critical */
	case critical = 'critical';

	/** Error */
	case error = 'error';

	/** Warning */
	case warning = 'warning';

	/** Notice */
	case notice = 'notice';

	/** Informational */
	case informational = 'informational';

	/** Debug */
	case debug = 'debug';
}
