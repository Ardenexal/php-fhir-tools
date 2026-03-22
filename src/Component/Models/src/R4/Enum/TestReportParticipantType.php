<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: TestReportParticipantType
 * URL: http://hl7.org/fhir/ValueSet/report-participant-type
 * Version: 4.0.1
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
