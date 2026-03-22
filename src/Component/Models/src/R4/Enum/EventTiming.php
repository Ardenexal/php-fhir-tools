<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: EventTiming
 * URL: http://hl7.org/fhir/ValueSet/event-timing
 * Version: 4.0.1
 * Description: Real world event relating to the schedule.
 */
enum EventTiming: string
{
    /** Morning */
    case morning = 'MORN';

    /** Early Morning */
    case earlymorning = 'MORN.early';

    /** Late Morning */
    case latemorning = 'MORN.late';

    /** Noon */
    case noon = 'NOON';

    /** Afternoon */
    case afternoon = 'AFT';

    /** Early Afternoon */
    case earlyafternoon = 'AFT.early';

    /** Late Afternoon */
    case lateafternoon = 'AFT.late';

    /** Evening */
    case evening = 'EVE';

    /** Early Evening */
    case earlyevening = 'EVE.early';

    /** Late Evening */
    case lateevening = 'EVE.late';

    /** Night */
    case night = 'NIGHT';

    /** After Sleep */
    case aftersleep = 'PHS';

    /** AC */
    case ac = 'AC';

    /** ACD */
    case acd = 'ACD';

    /** ACM */
    case acm = 'ACM';

    /** ACV */
    case acv = 'ACV';

    /** C */
    case c = 'C';

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

    /** WAKE */
    case wake = 'WAKE';

    /** CM */
    case cm = 'CM';

    /** CD */
    case cd = 'CD';

    /** CV */
    case cv = 'CV';
}
