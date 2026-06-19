<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDARoleCode
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDARoleCode
 * Version: 2.0.2-sd
 * Description: A set of codes further specifying the kind of Role; specific classification codes for further qualifying RoleClass codes.
 */
enum RoleCode: string
{
    /** DX */
    case dx = 'DX';

    /** CVDX */
    case cvdx = 'CVDX';

    /** CATH */
    case cath = 'CATH';

    /** ECHO */
    case echo = 'ECHO';

    /** GIDX */
    case gidx = 'GIDX';

    /** ENDOS */
    case endos = 'ENDOS';

    /** RADDX */
    case raddx = 'RADDX';

    /** RADO */
    case rado = 'RADO';

    /** RNEU */
    case rneu = 'RNEU';

    /** HOSP */
    case hosp = 'HOSP';

    /** CHR */
    case chr = 'CHR';

    /** GACH */
    case gach = 'GACH';

    /** MHSP */
    case mhsp = 'MHSP';

    /** PSYCHF */
    case psychf = 'PSYCHF';

    /** RH */
    case rh = 'RH';

    /** RHAT */
    case rhat = 'RHAT';

    /** RHII */
    case rhii = 'RHII';

    /** RHMAD */
    case rhmad = 'RHMAD';

    /** RHPI */
    case rhpi = 'RHPI';

    /** RHPIH */
    case rhpih = 'RHPIH';

    /** RHPIMS */
    case rhpims = 'RHPIMS';

    /** RHPIVS */
    case rhpivs = 'RHPIVS';

    /** RHYAD */
    case rhyad = 'RHYAD';

    /** HU */
    case hu = 'HU';

    /** BMTU */
    case bmtu = 'BMTU';

    /** CCU */
    case ccu = 'CCU';

    /** CHEST */
    case chest = 'CHEST';

    /** EPIL */
    case epil = 'EPIL';

    /** ER */
    case er = 'ER';

    /** ETU */
    case etu = 'ETU';

    /** HD */
    case hd = 'HD';

    /** HLAB */
    case hlab = 'HLAB';

    /** INLAB */
    case inlab = 'INLAB';

    /** OUTLAB */
    case outlab = 'OUTLAB';

    /** HRAD */
    case hrad = 'HRAD';

    /** HUSCS */
    case huscs = 'HUSCS';

    /** ICU */
    case icu = 'ICU';

    /** PEDICU */
    case pedicu = 'PEDICU';

    /** PEDNICU */
    case pednicu = 'PEDNICU';

    /** INPHARM */
    case inpharm = 'INPHARM';

    /** MBL */
    case mbl = 'MBL';

    /** NCCS */
    case nccs = 'NCCS';

    /** NS */
    case ns = 'NS';

    /** OUTPHARM */
    case outpharm = 'OUTPHARM';

    /** PEDU */
    case pedu = 'PEDU';

    /** PHU */
    case phu = 'PHU';

    /** RHU */
    case rhu = 'RHU';

    /** SLEEP */
    case sleep = 'SLEEP';

    /** NCCF */
    case nccf = 'NCCF';

    /** SNF */
    case snf = 'SNF';

    /** OF */
    case of = 'OF';

    /** ALL */
    case all = 'ALL';

    /** AMPUT */
    case amput = 'AMPUT';

    /** BMTC */
    case bmtc = 'BMTC';

    /** BREAST */
    case breast = 'BREAST';

    /** CANC */
    case canc = 'CANC';

    /** CAPC */
    case capc = 'CAPC';

    /** CARD */
    case card = 'CARD';

    /** PEDCARD */
    case pedcard = 'PEDCARD';

    /** COAG */
    case coag = 'COAG';

    /** CRS */
    case crs = 'CRS';

    /** DERM */
    case derm = 'DERM';

    /** ENDO */
    case endo = 'ENDO';

    /** PEDE */
    case pede = 'PEDE';

    /** ENT */
    case ent = 'ENT';

    /** FMC */
    case fmc = 'FMC';

    /** GI */
    case gi = 'GI';

    /** PEDGI */
    case pedgi = 'PEDGI';

    /** GIM */
    case gim = 'GIM';

    /** GYN */
    case gyn = 'GYN';

    /** HEM */
    case hem = 'HEM';

    /** PEDHEM */
    case pedhem = 'PEDHEM';

    /** HTN */
    case htn = 'HTN';

    /** IEC */
    case iec = 'IEC';

    /** INFD */
    case infd = 'INFD';

    /** PEDID */
    case pedid = 'PEDID';

    /** INV */
    case inv = 'INV';

    /** LYMPH */
    case lymph = 'LYMPH';

    /** MGEN */
    case mgen = 'MGEN';

    /** NEPH */
    case neph = 'NEPH';

    /** PEDNEPH */
    case pedneph = 'PEDNEPH';

    /** NEUR */
    case neur = 'NEUR';

    /** OB */
    case ob = 'OB';

    /** OMS */
    case oms = 'OMS';

    /** ONCL */
    case oncl = 'ONCL';

    /** PEDHO */
    case pedho = 'PEDHO';

    /** OPH */
    case oph = 'OPH';

    /** OPTC */
    case optc = 'OPTC';

    /** ORTHO */
    case ortho = 'ORTHO';

    /** HAND */
    case hand = 'HAND';

    /** PAINCL */
    case paincl = 'PAINCL';

    /** PC */
    case pc = 'PC';

    /** PEDC */
    case pedc = 'PEDC';

    /** PEDRHEUM */
    case pedrheum = 'PEDRHEUM';

    /** POD */
    case pod = 'POD';

    /** PREV */
    case prev = 'PREV';

    /** PROCTO */
    case procto = 'PROCTO';

    /** PROFF */
    case proff = 'PROFF';

    /** PROS */
    case pros = 'PROS';

    /** PSI */
    case psi = 'PSI';

    /** PSY */
    case psy = 'PSY';

    /** RHEUM */
    case rheum = 'RHEUM';

    /** SPMED */
    case spmed = 'SPMED';

    /** SU */
    case su = 'SU';

    /** PLS */
    case pls = 'PLS';

    /** URO */
    case uro = 'URO';

    /** TR */
    case tr = 'TR';

    /** TRAVEL */
    case travel = 'TRAVEL';

    /** WND */
    case wnd = 'WND';

    /** RTF */
    case rtf = 'RTF';

    /** PRC */
    case prc = 'PRC';

    /** SURF */
    case surf = 'SURF';

    /** DADDR */
    case daddr = 'DADDR';

    /** MOBL */
    case mobl = 'MOBL';

    /** AMB */
    case amb = 'AMB';

    /** PHARM */
    case pharm = 'PHARM';

    /** ACC */
    case acc = 'ACC';

    /** COMM */
    case comm = 'COMM';

    /** CSC */
    case csc = 'CSC';

    /** PTRES */
    case ptres = 'PTRES';

    /** SCHOOL */
    case school = 'SCHOOL';

    /** UPC */
    case upc = 'UPC';

    /** WORK */
    case work = 'WORK';
}
