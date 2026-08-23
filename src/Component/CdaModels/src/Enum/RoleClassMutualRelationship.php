<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDARoleClassMutualRelationship
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDARoleClassMutualRelationship
 * Version: 2.0.2-sd
 * Description: A relationship that is based on mutual behavior of the two Entities as being related. The basis of such relationship may be agreements (e.g., spouses, contract parties) or they may be de facto behavior (e.g. friends) or may be an incidental involvement with each other (e.g. parties over a dispute, siblings, children).
 */
enum RoleClassMutualRelationship: string
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
}
