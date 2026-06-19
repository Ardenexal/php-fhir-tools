<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAObservationInterpretation
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAObservationInterpretation
 * Version: 2.0.2-sd
 * Description: One or more codes providing a rough qualitative interpretation of the observation - limited to values available in original CDA
 */
enum ObservationInterpretation: string
{
    /** B */
    case b = 'B';

    /** D */
    case d = 'D';

    /** U */
    case u = 'U';

    /** W */
    case w = 'W';

    /** < */
    case lessthan = '<';

    /** > */
    case greaterthan = '>';

    /** A */
    case a = 'A';

    /** AA */
    case aa = 'AA';

    /** HH */
    case hh = 'HH';

    /** LL */
    case ll = 'LL';

    /** H */
    case h = 'H';

    /** L */
    case l = 'L';

    /** N */
    case n = 'N';

    /** I */
    case i = 'I';

    /** MS */
    case ms = 'MS';

    /** R */
    case r = 'R';

    /** S */
    case s = 'S';

    /** VS */
    case vs = 'VS';
}
