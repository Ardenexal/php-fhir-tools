<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDATimingEvent
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDATimingEvent
 * Version: 2.0.2-sd
 * Description: A set of codes for common (periodical) activity of daily living - limited to values allowed in original CDA definition
 */
enum TimingEvent: string
{
    /** AC */
    case ac = 'AC';

    /** ACD */
    case acd = 'ACD';

    /** ACM */
    case acm = 'ACM';

    /** ACV */
    case acv = 'ACV';

    /** HS */
    case hs = 'HS';

    /** IC */
    case ic = 'IC';

    /** ICD */
    case icd = 'ICD';

    /** ICM */
    case icm = 'ICM';

    /** ICV */
    case icv = 'ICV';

    /** PC */
    case pc = 'PC';

    /** PCD */
    case pcd = 'PCD';

    /** PCM */
    case pcm = 'PCM';

    /** PCV */
    case pcv = 'PCV';
}
