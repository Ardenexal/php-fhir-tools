<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDAActClassObservation
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAActClassObservation
 * Version: 2.0.2-sd
 * Description: An act that is intended to result in new information about a subject. The main difference between Observations and other Acts is that Observations have a value attribute.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDAActClassObservation', version: '2.0.2-sd')]
enum ActClassObservation: string
{
    /** CASE */
    case case = 'CASE';

    /** OUTB */
    case outb = 'OUTB';

    /** COND */
    case cond = 'COND';

    /** OBSSER */
    case obsser = 'OBSSER';

    /** OBSCOR */
    case obscor = 'OBSCOR';

    /** ROIBND */
    case roibnd = 'ROIBND';

    /** ROIOVL */
    case roiovl = 'ROIOVL';

    /** OBS */
    case obs = 'OBS';

    /** ALRT */
    case alrt = 'ALRT';

    /** CLNTRL */
    case clntrl = 'CLNTRL';

    /** CNOD */
    case cnod = 'CNOD';

    /** DGIMG */
    case dgimg = 'DGIMG';

    /** INVSTG */
    case invstg = 'INVSTG';

    /** SPCOBS */
    case spcobs = 'SPCOBS';
}
