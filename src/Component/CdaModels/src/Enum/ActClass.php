<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAActClass
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAActClass
 * Version: 2.0.2-sd
 * Description: A code specifying the major type of Act that this Act-instance represents.
 */
enum ActClass: string
{
    /** FCNTRCT */
    case fcntrct = 'FCNTRCT';

    /** COV */
    case cov = 'COV';

    /** CNTRCT */
    case cntrct = 'CNTRCT';

    /** CACT */
    case cact = 'CACT';

    /** ACTN */
    case actn = 'ACTN';

    /** INFO */
    case info = 'INFO';

    /** STC */
    case stc = 'STC';

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

    /** SPLY */
    case sply = 'SPLY';

    /** DIET */
    case diet = 'DIET';

    /** DOCCLIN */
    case docclin = 'DOCCLIN';

    /** CDALVLONE */
    case cdalvlone = 'CDALVLONE';

    /** DOC */
    case doc = 'DOC';

    /** COMPOSITION */
    case composition = 'COMPOSITION';

    /** ENTRY */
    case entry = 'ENTRY';

    /** BATTERY */
    case battery = 'BATTERY';

    /** CLUSTER */
    case cluster = 'CLUSTER';

    /** EXTRACT */
    case extract = 'EXTRACT';

    /** EHR */
    case ehr = 'EHR';

    /** ORGANIZER */
    case organizer = 'ORGANIZER';

    /** CATEGORY */
    case category = 'CATEGORY';

    /** DOCBODY */
    case docbody = 'DOCBODY';

    /** DOCSECT */
    case docsect = 'DOCSECT';

    /** TOPIC */
    case topic = 'TOPIC';

    /** FOLDER */
    case folder = 'FOLDER';

    /** ACT */
    case act = 'ACT';

    /** ACCM */
    case accm = 'ACCM';

    /** CONS */
    case cons = 'CONS';

    /** CTTEVENT */
    case cttevent = 'CTTEVENT';

    /** INC */
    case inc = 'INC';

    /** INFRM */
    case infrm = 'INFRM';

    /** PCPR */
    case pcpr = 'PCPR';

    /** REG */
    case reg = 'REG';

    /** SPCTRT */
    case spctrt = 'SPCTRT';

    /** ACCT */
    case acct = 'ACCT';

    /** ACSN */
    case acsn = 'ACSN';

    /** ADJUD */
    case adjud = 'ADJUD';

    /** CONTREG */
    case contreg = 'CONTREG';

    /** DISPACT */
    case dispact = 'DISPACT';

    /** ENC */
    case enc = 'ENC';

    /** INVE */
    case inve = 'INVE';

    /** LIST */
    case list = 'LIST';

    /** MPROT */
    case mprot = 'MPROT';

    /** PROC */
    case proc = 'PROC';

    /** REV */
    case rev = 'REV';

    /** SBADM */
    case sbadm = 'SBADM';

    /** SUBST */
    case subst = 'SUBST';

    /** TRNS */
    case trns = 'TRNS';

    /** VERIF */
    case verif = 'VERIF';

    /** XACT */
    case xact = 'XACT';
}
