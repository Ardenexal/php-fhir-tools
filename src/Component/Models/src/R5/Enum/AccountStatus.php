<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Account Status
 * URL: http://hl7.org/fhir/ValueSet/account-status
 * Version: 5.0.0
 * Description: Indicates whether the account is available to be used.
 */
enum AccountStatus: string
{
	/** Active */
	case active = 'active';

	/** Inactive */
	case inactive = 'inactive';

	/** Entered in error */
	case enteredinerror = 'entered-in-error';

	/** On Hold */
	case onhold = 'on-hold';

	/** Unknown */
	case unknown = 'unknown';
}
