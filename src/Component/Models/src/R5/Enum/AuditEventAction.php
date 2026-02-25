<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Audit Event Action
 * URL: http://hl7.org/fhir/ValueSet/audit-event-action
 * Version: 5.0.0
 * Description: Indicator for type of action performed during the event that generated the event.
 */
enum AuditEventAction: string
{
	/** Create */
	case create = 'C';

	/** Read */
	case read = 'R';

	/** Update */
	case update = 'U';

	/** Delete */
	case delete = 'D';

	/** Execute */
	case execute = 'E';
}
