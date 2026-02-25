<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Criteria Not Exists Behavior
 * URL: http://hl7.org/fhir/ValueSet/subscriptiontopic-cr-behavior
 * Version: 5.0.0
 * Description: Behavior a server can exhibit when a criteria state does not exist (e.g., state prior to a create or after a delete).
 */
enum CriteriaNotExistsBehavior: string
{
	/** Test passes */
	case testpasses = 'test-passes';

	/** Test fails */
	case testfails = 'test-fails';
}
