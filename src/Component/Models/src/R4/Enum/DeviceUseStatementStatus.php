<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: DeviceUseStatementStatus
 * URL: http://hl7.org/fhir/ValueSet/device-statement-status
 * Version: 4.0.1
 * Description: A coded concept indicating the current status of the Device Usage.
 */
enum DeviceUseStatementStatus: string
{
    /** Active */
    case active = 'active';

    /** Completed */
    case completed = 'completed';

    /** Entered in Error */
    case enteredinerror = 'entered-in-error';

    /** Intended */
    case intended = 'intended';

    /** Stopped */
    case stopped = 'stopped';

    /** On Hold */
    case onhold = 'on-hold';
}
