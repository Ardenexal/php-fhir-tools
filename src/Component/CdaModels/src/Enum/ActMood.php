<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAActMood
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAActMood
 * Version: 2.0.2-sd
 * Description: A code distinguishing whether an Act is conceived of as a factual statement or in some other manner as a command, possibility, goal, etc.
 */
enum ActMood: string
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

    /** DEF */
    case def = 'DEF';

    /** EVN */
    case evn = 'EVN';

    /** EVN.CRT */
    case evncrt = 'EVN.CRT';

    /** GOL */
    case gol = 'GOL';

    /** OPT */
    case opt = 'OPT';

    /** PERM */
    case perm = 'PERM';

    /** PERMRQ */
    case permrq = 'PERMRQ';
}
