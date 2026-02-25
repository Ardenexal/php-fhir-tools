<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Consent State
 * URL: http://hl7.org/fhir/ValueSet/consent-state-codes
 * Version: 5.0.0
 * Description: Indicates the state of the consent.
 */
enum ConsentState: string
{
	/** Pending */
	case pending = 'draft';

	/** Active */
	case active = 'active';

	/** Inactive */
	case inactive = 'inactive';

	/** Abandoned */
	case abandoned = 'not-done';

	/** Entered in Error */
	case enteredinerror = 'entered-in-error';

	/** Unknown */
	case unknown = 'unknown';
}
