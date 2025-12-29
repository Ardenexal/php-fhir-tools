<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: Financial Resource Status Codes
 * URL: http://hl7.org/fhir/ValueSet/fm-status
 * Version: 4.3.0
 * Description: This value set includes Status codes.
 */
enum FHIRFinancialResourceStatusCodes: string
{
	/** Active */
	case active = 'active';

	/** Cancelled */
	case cancelled = 'cancelled';

	/** Draft */
	case draft = 'draft';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';
}
