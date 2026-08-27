<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDARoleClassRoot
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDARoleClassRoot
 * Version: 2.0.2-sd
 * Description: Corresponds to the Role class
 */
enum RoleClassRoot: string
{
    /** LIC */
    case lic = 'LIC';

    /** NOT */
    case not = 'NOT';

    /** PROV */
    case prov = 'PROV';

    /** CON */
    case con = 'CON';

    /** ECON */
    case econ = 'ECON';

    /** NOK */
    case nok = 'NOK';

    /** ASSIGNED */
    case assigned = 'ASSIGNED';

    /** COMPAR */
    case compar = 'COMPAR';

    /** SGNOFF */
    case sgnoff = 'SGNOFF';

    /** AGNT */
    case agnt = 'AGNT';

    /** GUARD */
    case guard = 'GUARD';

    /** EMP */
    case emp = 'EMP';

    /** MIL */
    case mil = 'MIL';

    /** INVSBJ */
    case invsbj = 'INVSBJ';

    /** CASESBJ */
    case casesbj = 'CASESBJ';

    /** RESBJ */
    case resbj = 'RESBJ';

    /** CIT */
    case cit = 'CIT';

    /** COVPTY */
    case covpty = 'COVPTY';

    /** CRINV */
    case crinv = 'CRINV';

    /** CRSPNSR */
    case crspnsr = 'CRSPNSR';

    /** GUAR */
    case guar = 'GUAR';

    /** PAT */
    case pat = 'PAT';

    /** PAYEE */
    case payee = 'PAYEE';

    /** PAYOR */
    case payor = 'PAYOR';

    /** POLHOLD */
    case polhold = 'POLHOLD';

    /** QUAL */
    case qual = 'QUAL';

    /** SPNSR */
    case spnsr = 'SPNSR';

    /** STD */
    case std = 'STD';

    /** UNDWRT */
    case undwrt = 'UNDWRT';

    /** CAREGIVER */
    case caregiver = 'CAREGIVER';

    /** PRS */
    case prs = 'PRS';

    /** DST */
    case dst = 'DST';

    /** RET */
    case ret = 'RET';

    /** MANU */
    case manu = 'MANU';

    /** THER */
    case ther = 'THER';

    /** SDLOC */
    case sdloc = 'SDLOC';

    /** DSDLOC */
    case dsdloc = 'DSDLOC';

    /** ISDLOC */
    case isdloc = 'ISDLOC';

    /** ACCESS */
    case access = 'ACCESS';

    /** BIRTHPL */
    case birthpl = 'BIRTHPL';

    /** EXPR */
    case expr = 'EXPR';

    /** HLD */
    case hld = 'HLD';

    /** HLTHCHRT */
    case hlthchrt = 'HLTHCHRT';

    /** IDENT */
    case ident = 'IDENT';

    /** MNT */
    case mnt = 'MNT';

    /** OWN */
    case own = 'OWN';

    /** RGPR */
    case rgpr = 'RGPR';

    /** TERR */
    case terr = 'TERR';

    /** WRTE */
    case wrte = 'WRTE';

    /** GEN */
    case gen = 'GEN';

    /** GRIC */
    case gric = 'GRIC';

    /** INST */
    case inst = 'INST';

    /** SUBS */
    case subs = 'SUBS';

    /** SUBY */
    case suby = 'SUBY';

    /** IACT */
    case iact = 'IACT';

    /** COLR */
    case colr = 'COLR';

    /** FLVR */
    case flvr = 'FLVR';

    /** PRSV */
    case prsv = 'PRSV';

    /** STBL */
    case stbl = 'STBL';

    /** INGR */
    case ingr = 'INGR';

    /** ACTI */
    case acti = 'ACTI';

    /** ACTM */
    case actm = 'ACTM';

    /** ADTV */
    case adtv = 'ADTV';

    /** BASE */
    case base = 'BASE';

    /** LOCE */
    case loce = 'LOCE';

    /** STOR */
    case stor = 'STOR';

    /** SPEC */
    case spec = 'SPEC';

    /** ALQT */
    case alqt = 'ALQT';

    /** ISLT */
    case islt = 'ISLT';

    /** CONT */
    case cont = 'CONT';

    /** MBR */
    case mbr = 'MBR';

    /** PART */
    case part = 'PART';

    /** ROL */
    case rol = 'ROL';
}
