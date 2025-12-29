<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Permission Rule Combining
 * URL: http://hl7.org/fhir/ValueSet/permission-rule-combining
 * Version: 5.0.0
 * Description: Codes identifying rule combining algorithm.
 */
enum FHIRPermissionRuleCombining: string
{
	/** Deny-overrides */
	case denyoverrides = 'deny-overrides';

	/** Permit-overrides */
	case permitoverrides = 'permit-overrides';

	/** Ordered-deny-overrides */
	case ordereddenyoverrides = 'ordered-deny-overrides';

	/** Ordered-permit-overrides */
	case orderedpermitoverrides = 'ordered-permit-overrides';

	/** Deny-unless-permit */
	case denyunlesspermit = 'deny-unless-permit';

	/** Permit-unless-deny */
	case permitunlessdeny = 'permit-unless-deny';
}
