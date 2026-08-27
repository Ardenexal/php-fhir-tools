<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAActMoodIntent
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAActMoodIntent
 * Version: 2.0.2-sd
 * Description: An intention or plan to perform a service.
 */
enum ActMoodIntent: string
{
    /** INT */
    case int = 'INT';

    /** APT */
    case apt = 'APT';

    /** ARQ */
    case arq = 'ARQ';

    /** PRMS */
    case prms = 'PRMS';

    /** PRP */
    case prp = 'PRP';

    /** RQO */
    case rqo = 'RQO';

    /** SLOT */
    case slot = 'SLOT';
}
