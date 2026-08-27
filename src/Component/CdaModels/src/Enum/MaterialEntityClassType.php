<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDAMaterialEntityClassType
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAMaterialEntityClassType
 * Version: 2.0.2-sd
 * Description: Types of Material for EntityClass “MAT”
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDAMaterialEntityClassType', version: '2.0.2-sd')]
enum MaterialEntityClassType: string
{
    /** PKG */
    case pkg = 'PKG';

    /** BAG */
    case bag = 'BAG';

    /** PACKT */
    case packt = 'PACKT';

    /** PCH */
    case pch = 'PCH';

    /** SACH */
    case sach = 'SACH';

    /** AMP */
    case amp = 'AMP';

    /** MINIM */
    case minim = 'MINIM';

    /** NEBAMP */
    case nebamp = 'NEBAMP';

    /** OVUL */
    case ovul = 'OVUL';

    /** BOT */
    case bot = 'BOT';

    /** BOTA */
    case bota = 'BOTA';

    /** BOTD */
    case botd = 'BOTD';

    /** BOTG */
    case botg = 'BOTG';

    /** BOTP */
    case botp = 'BOTP';

    /** BOTPLY */
    case botply = 'BOTPLY';

    /** BOX */
    case box = 'BOX';

    /** CAN */
    case can = 'CAN';

    /** CART */
    case cart = 'CART';

    /** CNSTR */
    case cnstr = 'CNSTR';

    /** JAR */
    case jar = 'JAR';

    /** JUG */
    case jug = 'JUG';

    /** TIN */
    case tin = 'TIN';

    /** TUB */
    case tub = 'TUB';

    /** TUBE */
    case tube = 'TUBE';

    /** VIAL */
    case vial = 'VIAL';

    /** BLSTRPK */
    case blstrpk = 'BLSTRPK';

    /** CARD */
    case card = 'CARD';

    /** COMPPKG */
    case comppkg = 'COMPPKG';

    /** DIALPK */
    case dialpk = 'DIALPK';

    /** DISK */
    case disk = 'DISK';

    /** DOSET */
    case doset = 'DOSET';

    /** STRIP */
    case strip = 'STRIP';

    /** KIT */
    case kit = 'KIT';

    /** SYSTM */
    case systm = 'SYSTM';

    /** LINE */
    case line = 'LINE';

    /** IALINE */
    case ialine = 'IALINE';

    /** IVLINE */
    case ivline = 'IVLINE';

    /** AINJ */
    case ainj = 'AINJ';

    /** PEN */
    case pen = 'PEN';

    /** SYR */
    case syr = 'SYR';

    /** APLCTR */
    case aplctr = 'APLCTR';

    /** INH */
    case inh = 'INH';

    /** DSKS */
    case dsks = 'DSKS';

    /** DSKUNH */
    case dskunh = 'DSKUNH';

    /** TRBINH */
    case trbinh = 'TRBINH';

    /** PMP */
    case pmp = 'PMP';

    /** ACDA */
    case acda = 'ACDA';

    /** ACDB */
    case acdb = 'ACDB';

    /** ACET */
    case acet = 'ACET';

    /** AMIES */
    case amies = 'AMIES';

    /** BACTM */
    case bactm = 'BACTM';

    /** BF10 */
    case bf10 = 'BF10';

    /** BOR */
    case bor = 'BOR';

    /** BOUIN */
    case bouin = 'BOUIN';

    /** BSKM */
    case bskm = 'BSKM';

    /** C32 */
    case c32 = 'C32';

    /** C38 */
    case c38 = 'C38';

    /** CARS */
    case cars = 'CARS';

    /** CARY */
    case cary = 'CARY';

    /** CHLTM */
    case chltm = 'CHLTM';

    /** CTAD */
    case ctad = 'CTAD';

    /** EDTK15 */
    case edtk15 = 'EDTK15';

    /** EDTK75 */
    case edtk75 = 'EDTK75';

    /** EDTN */
    case edtn = 'EDTN';

    /** ENT */
    case ent = 'ENT';

    /** F10 */
    case f10 = 'F10';

    /** FDP */
    case fdp = 'FDP';

    /** FL10 */
    case fl10 = 'FL10';

    /** FL100 */
    case fl100 = 'FL100';

    /** HCL6 */
    case hcl6 = 'HCL6';

    /** HEPA */
    case hepa = 'HEPA';

    /** HEPL */
    case hepl = 'HEPL';

    /** HEPN */
    case hepn = 'HEPN';

    /** HNO3 */
    case hno3 = 'HNO3';

    /** JKM */
    case jkm = 'JKM';

    /** KARN */
    case karn = 'KARN';

    /** KOX */
    case kox = 'KOX';

    /** LIA */
    case lia = 'LIA';

    /** M4 */
    case m4 = 'M4';

    /** M4RT */
    case m4_rt = 'M4RT';

    /** M5 */
    case m5 = 'M5';

    /** MICHTM */
    case michtm = 'MICHTM';

    /** MMDTM */
    case mmdtm = 'MMDTM';

    /** NAF */
    case naf = 'NAF';

    /** NONE */
    case none = 'NONE';

    /** PAGE */
    case page = 'PAGE';

    /** PHENOL */
    case phenol = 'PHENOL';

    /** PVA */
    case pva = 'PVA';

    /** RLM */
    case rlm = 'RLM';

    /** SILICA */
    case silica = 'SILICA';

    /** SPS */
    case sps = 'SPS';

    /** SST */
    case sst = 'SST';

    /** STUTM */
    case stutm = 'STUTM';

    /** THROM */
    case throm = 'THROM';

    /** THYMOL */
    case thymol = 'THYMOL';

    /** THYO */
    case thyo = 'THYO';

    /** TOLU */
    case tolu = 'TOLU';

    /** URETM */
    case uretm = 'URETM';

    /** VIRTM */
    case virtm = 'VIRTM';

    /** WEST */
    case west = 'WEST';

    /** BLDPRD */
    case bldprd = 'BLDPRD';

    /** VCCNE */
    case vccne = 'VCCNE';

    /** ABS */
    case abs = 'ABS';

    /** AMN */
    case amn = 'AMN';

    /** ASP */
    case asp = 'ASP';

    /** BBL */
    case bbl = 'BBL';

    /** BDY */
    case bdy = 'BDY';

    /** BIFL */
    case bifl = 'BIFL';

    /** BLD */
    case bld = 'BLD';

    /** BLDA */
    case blda = 'BLDA';

    /** BLDC */
    case bldc = 'BLDC';

    /** BLDCO */
    case bldco = 'BLDCO';

    /** BLDV */
    case bldv = 'BLDV';

    /** BON */
    case bon = 'BON';

    /** BPH */
    case bph = 'BPH';

    /** BPU */
    case bpu = 'BPU';

    /** BRN */
    case brn = 'BRN';

    /** BRO */
    case bro = 'BRO';

    /** BRTH */
    case brth = 'BRTH';

    /** EXG */
    case exg = 'EXG';

    /** CALC */
    case calc = 'CALC';

    /** STON */
    case ston = 'STON';

    /** CDM */
    case cdm = 'CDM';

    /** CNJT */
    case cnjt = 'CNJT';

    /** CNL */
    case cnl = 'CNL';

    /** COL */
    case col = 'COL';

    /** CRN */
    case crn = 'CRN';

    /** CSF */
    case csf = 'CSF';

    /** CTP */
    case ctp = 'CTP';

    /** CUR */
    case cur = 'CUR';

    /** CVM */
    case cvm = 'CVM';

    /** CVX */
    case cvx = 'CVX';

    /** CYST */
    case cyst = 'CYST';

    /** DIAF */
    case diaf = 'DIAF';

    /** DOSE */
    case dose = 'DOSE';

    /** DRN */
    case drn = 'DRN';

    /** DUFL */
    case dufl = 'DUFL';

    /** EAR */
    case ear = 'EAR';

    /** EARW */
    case earw = 'EARW';

    /** ELT */
    case elt = 'ELT';

    /** ENDC */
    case endc = 'ENDC';

    /** ENDM */
    case endm = 'ENDM';

    /** EOS */
    case eos = 'EOS';

    /** EYE */
    case eye = 'EYE';

    /** FIB */
    case fib = 'FIB';

    /** FIST */
    case fist = 'FIST';

    /** FLT */
    case flt = 'FLT';

    /** FLU */
    case flu = 'FLU';

    /** FOOD */
    case food = 'FOOD';

    /** GAS */
    case gas = 'GAS';

    /** GAST */
    case gast = 'GAST';

    /** GEN */
    case gen = 'GEN';

    /** GENC */
    case genc = 'GENC';

    /** GENF */
    case genf = 'GENF';

    /** GENL */
    case genl = 'GENL';

    /** GENV */
    case genv = 'GENV';

    /** HAR */
    case har = 'HAR';

    /** IHG */
    case ihg = 'IHG';

    /** ISLT */
    case islt = 'ISLT';

    /** IT */
    case it = 'IT';

    /** LAM */
    case lam = 'LAM';

    /** LIQ */
    case liq = 'LIQ';

    /** LN */
    case ln = 'LN';

    /** LNA */
    case lna = 'LNA';

    /** LNV */
    case lnv = 'LNV';

    /** LYM */
    case lym = 'LYM';

    /** MAC */
    case mac = 'MAC';

    /** MAR */
    case mar = 'MAR';

    /** MBLD */
    case mbld = 'MBLD';

    /** MEC */
    case mec = 'MEC';

    /** MILK */
    case milk = 'MILK';

    /** MLK */
    case mlk = 'MLK';

    /** NAIL */
    case nail = 'NAIL';

    /** NOS */
    case nos = 'NOS';

    /** PAFL */
    case pafl = 'PAFL';

    /** PAT */
    case pat = 'PAT';

    /** PLAS */
    case plas = 'PLAS';

    /** PLB */
    case plb = 'PLB';

    /** PLC */
    case plc = 'PLC';

    /** PLR */
    case plr = 'PLR';

    /** PMN */
    case pmn = 'PMN';

    /** PPP */
    case ppp = 'PPP';

    /** PRP */
    case prp = 'PRP';

    /** PRT */
    case prt = 'PRT';

    /** PUS */
    case pus = 'PUS';

    /** RBC */
    case rbc = 'RBC';

    /** SAL */
    case sal = 'SAL';

    /** SER */
    case ser = 'SER';

    /** SKM */
    case skm = 'SKM';

    /** SKN */
    case skn = 'SKN';

    /** SMN */
    case smn = 'SMN';

    /** SMPLS */
    case smpls = 'SMPLS';

    /** SNV */
    case snv = 'SNV';

    /** SPRM */
    case sprm = 'SPRM';

    /** SPT */
    case spt = 'SPT';

    /** SPTC */
    case sptc = 'SPTC';

    /** SPTT */
    case sptt = 'SPTT';

    /** STL */
    case stl = 'STL';

    /** SWT */
    case swt = 'SWT';

    /** TEAR */
    case tear = 'TEAR';

    /** THRB */
    case thrb = 'THRB';

    /** THRT */
    case thrt = 'THRT';

    /** TISG */
    case tisg = 'TISG';

    /** TISPL */
    case tispl = 'TISPL';

    /** TISS */
    case tiss = 'TISS';

    /** TISU */
    case tisu = 'TISU';

    /** TLGI */
    case tlgi = 'TLGI';

    /** TLNG */
    case tlng = 'TLNG';

    /** TSMI */
    case tsmi = 'TSMI';

    /** ULC */
    case ulc = 'ULC';

    /** UMB */
    case umb = 'UMB';

    /** UMED */
    case umed = 'UMED';

    /** UR */
    case ur = 'UR';

    /** URC */
    case urc = 'URC';

    /** URNS */
    case urns = 'URNS';

    /** URT */
    case urt = 'URT';

    /** URTH */
    case urth = 'URTH';

    /** USUB */
    case usub = 'USUB';

    /** VOM */
    case vom = 'VOM';

    /** WAT */
    case wat = 'WAT';

    /** WBC */
    case wbc = 'WBC';

    /** WICK */
    case wick = 'WICK';

    /** WND */
    case wnd = 'WND';

    /** WNDA */
    case wnda = 'WNDA';

    /** WNDD */
    case wndd = 'WNDD';

    /** WNDE */
    case wnde = 'WNDE';
}
