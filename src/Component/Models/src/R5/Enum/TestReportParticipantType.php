<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Test Report Participant Type
 * URL: http://hl7.org/fhir/ValueSet/report-participant-type
 * Version: 5.0.0
 * Description: The type of participant.
 */
enum TestReportParticipantType: string
{
	/** Test Engine */
	case testengine = 'test-engine';

	/** Client */
	case client = 'client';

	/** Server */
	case server = 'server';
}
