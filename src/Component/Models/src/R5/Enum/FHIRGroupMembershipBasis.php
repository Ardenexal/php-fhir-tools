<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Group Membership Basis
 * URL: http://hl7.org/fhir/ValueSet/group-membership-basis
 * Version: 5.0.0
 * Description: Basis for membership in a group
 */
enum FHIRGroupMembershipBasis: string
{
	/** Definitional */
	case definitional = 'definitional';

	/** Enumerated */
	case enumerated = 'enumerated';
}
