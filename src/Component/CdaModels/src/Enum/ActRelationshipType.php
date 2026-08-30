<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDAActRelationshipType
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAActRelationshipType
 * Version: 2.0.2-sd
 * Description: A code specifying the meaning and purpose of every ActRelationship instance. Each of its values implies specific constraints to what kinds of Act objects can be related and in which way.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDAActRelationshipType', version: '2.0.2-sd')]
enum ActRelationshipType: string
{
    /** RSON */
    case rson = 'RSON';

    /** MITGT */
    case mitgt = 'MITGT';

    /** CIND */
    case cind = 'CIND';

    /** PRCN */
    case prcn = 'PRCN';

    /** TRIG */
    case trig = 'TRIG';

    /** COMP */
    case comp = 'COMP';

    /** ARR */
    case arr = 'ARR';

    /** CTRLV */
    case ctrlv = 'CTRLV';

    /** DEP */
    case dep = 'DEP';

    /** OBJC */
    case objc = 'OBJC';

    /** OBJF */
    case objf = 'OBJF';

    /** OUTC */
    case outc = 'OUTC';

    /** GOAL */
    case goal = 'GOAL';

    /** RISK */
    case risk = 'RISK';

    /** CHRG */
    case chrg = 'CHRG';

    /** COST */
    case cost = 'COST';

    /** CREDIT */
    case credit = 'CREDIT';

    /** DEBIT */
    case debit = 'DEBIT';

    /** SAS */
    case sas = 'SAS';

    /** SPRT */
    case sprt = 'SPRT';

    /** SPRTBND */
    case sprtbnd = 'SPRTBND';

    /** PERT */
    case pert = 'PERT';

    /** AUTH */
    case auth = 'AUTH';

    /** CAUS */
    case caus = 'CAUS';

    /** COVBY */
    case covby = 'COVBY';

    /** DRIV */
    case driv = 'DRIV';

    /** EXPL */
    case expl = 'EXPL';

    /** ITEMSLOC */
    case itemsloc = 'ITEMSLOC';

    /** LIMIT */
    case limit = 'LIMIT';

    /** MFST */
    case mfst = 'MFST';

    /** NAME */
    case name = 'NAME';

    /** PREV */
    case prev = 'PREV';

    /** REFR */
    case refr = 'REFR';

    /** REFV */
    case refv = 'REFV';

    /** SUBJ */
    case subj = 'SUBJ';

    /** SUMM */
    case summ = 'SUMM';

    /** XCRPT */
    case xcrpt = 'XCRPT';

    /** VRXCRPT */
    case vrxcrpt = 'VRXCRPT';

    /** FLFS */
    case flfs = 'FLFS';

    /** OCCR */
    case occr = 'OCCR';

    /** OREF */
    case oref = 'OREF';

    /** SCH */
    case sch = 'SCH';

    /** RPLC */
    case rplc = 'RPLC';

    /** SUCC */
    case succ = 'SUCC';

    /** SEQL */
    case seql = 'SEQL';

    /** APND */
    case apnd = 'APND';

    /** DOC */
    case doc = 'DOC';

    /** ELNK */
    case elnk = 'ELNK';

    /** GEN */
    case gen = 'GEN';

    /** GEVL */
    case gevl = 'GEVL';

    /** INST */
    case inst = 'INST';

    /** MTCH */
    case mtch = 'MTCH';

    /** OPTN */
    case optn = 'OPTN';

    /** REV */
    case rev = 'REV';

    /** UPDT */
    case updt = 'UPDT';

    /** XFRM */
    case xfrm = 'XFRM';
}
