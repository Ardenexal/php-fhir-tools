<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Research Subject State
 * URL: http://hl7.org/fhir/ValueSet/research-subject-state
 * Version: 5.0.0
 * Description: Indicates the progression of a study subject through a study.
 */
enum FHIRResearchSubjectState: string
{
	/** Candidate */
	case candidate = 'candidate';

	/** Eligible */
	case eligible = 'eligible';

	/** Follow-up */
	case followup = 'follow-up';

	/** Ineligible */
	case ineligible = 'ineligible';

	/** Not Registered */
	case notregistered = 'not-registered';

	/** Off-study */
	case offstudy = 'off-study';

	/** On-study */
	case onstudy = 'on-study';

	/** On-study-intervention */
	case onstudyintervention = 'on-study-intervention';

	/** On-study-observation */
	case onstudyobservation = 'on-study-observation';

	/** Pending on-study */
	case pendingonstudy = 'pending-on-study';

	/** Potential Candidate */
	case potentialcandidate = 'potential-candidate';

	/** Screening */
	case screening = 'screening';

	/** Withdrawn */
	case withdrawn = 'withdrawn';
}
