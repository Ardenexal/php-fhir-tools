<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAEntityNameUse
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAEntityNameUse
 * Version: 2.0.2-sd
 * Description: A set of codes advising a system or user which name in a set of names to select for a given purpose - limited to values allowed in original CDA definition
 */
enum EntityNameUse: string
{
    /** C */
    case c = 'C';

    /** L */
    case l = 'L';

    /** I */
    case i = 'I';

    /** P */
    case p = 'P';

    /** A */
    case a = 'A';

    /** R */
    case r = 'R';

    /** SRCH */
    case srch = 'SRCH';

    /** PHON */
    case phon = 'PHON';

    /** SNDX */
    case sndx = 'SNDX';

    /** ASGN */
    case asgn = 'ASGN';

    /** ABC */
    case abc = 'ABC';

    /** SYL */
    case syl = 'SYL';

    /** IDE */
    case ide = 'IDE';
}
