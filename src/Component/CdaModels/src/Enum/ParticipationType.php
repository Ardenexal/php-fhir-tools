<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAParticipationType
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAParticipationType
 * Version: 2.0.2-sd
 * Description: A code specifying the meaning and purpose of every Participation instance. Each of its values implies specific constraints on the Roles undertaking the participation. Limited to values allowed in original CDA definition
 */
enum ParticipationType: string
{
    /** ADM */
    case adm = 'ADM';

    /** ATND */
    case atnd = 'ATND';

    /** CALLBCK */
    case callbck = 'CALLBCK';

    /** CON */
    case con = 'CON';

    /** DIS */
    case dis = 'DIS';

    /** ESC */
    case esc = 'ESC';

    /** REF */
    case ref = 'REF';

    /** IND */
    case ind = 'IND';

    /** BEN */
    case ben = 'BEN';

    /** COV */
    case cov = 'COV';

    /** HLD */
    case hld = 'HLD';

    /** RCT */
    case rct = 'RCT';

    /** RCV */
    case rcv = 'RCV';

    /** AUT */
    case aut = 'AUT';

    /** ENT */
    case ent = 'ENT';

    /** INF */
    case inf = 'INF';

    /** WIT */
    case wit = 'WIT';

    /** IRCP */
    case ircp = 'IRCP';

    /** NOT */
    case not = 'NOT';

    /** PRCP */
    case prcp = 'PRCP';

    /** REFB */
    case refb = 'REFB';

    /** REFT */
    case reft = 'REFT';

    /** TRC */
    case trc = 'TRC';

    /** PRF */
    case prf = 'PRF';

    /** DIST */
    case dist = 'DIST';

    /** PPRF */
    case pprf = 'PPRF';

    /** SPRF */
    case sprf = 'SPRF';

    /** DEV */
    case dev = 'DEV';

    /** NRD */
    case nrd = 'NRD';

    /** RDV */
    case rdv = 'RDV';

    /** SBJ */
    case sbj = 'SBJ';

    /** SPC */
    case spc = 'SPC';

    /** DIR */
    case dir = 'DIR';

    /** BBY */
    case bby = 'BBY';

    /** CSM */
    case csm = 'CSM';

    /** DON */
    case don = 'DON';

    /** PRD */
    case prd = 'PRD';

    /** LOC */
    case loc = 'LOC';

    /** DST */
    case dst = 'DST';

    /** ELOC */
    case eloc = 'ELOC';

    /** ORG */
    case org = 'ORG';

    /** RML */
    case rml = 'RML';

    /** VIA */
    case via = 'VIA';

    /** VRF */
    case vrf = 'VRF';

    /** AUTHEN */
    case authen = 'AUTHEN';

    /** LA */
    case la = 'LA';

    /** RESP */
    case resp = 'RESP';

    /** CST */
    case cst = 'CST';
}
