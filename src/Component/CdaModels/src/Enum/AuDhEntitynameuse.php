<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Entity Name Use
 * URL: http://ns.electronichealth.net.au/cda/ValueSet/dh-entitynameuse
 * Version: 1.0.1
 * Description: Extended Entity Name Use code value set with Organisation Name Use Value from AS 4846-2006 Organisation Name Usage
 */
#[FHIRValueSetSource(url: 'http://ns.electronichealth.net.au/cda/ValueSet/dh-entitynameuse', version: '1.0.1')]
enum AuDhEntitynameuse: string
{
    /** ABC */
    case abc = 'ABC';

    /** C */
    case c = 'C';

    /** IDE */
    case ide = 'IDE';

    /** L */
    case l = 'L';

    /** OR */
    case or = 'OR';

    /** SYL */
    case syl = 'SYL';

    /** A */
    case a = 'A';

    /** I */
    case i = 'I';

    /** P */
    case p = 'P';

    /** R */
    case r = 'R';

    /** PHON */
    case phon = 'PHON';

    /** SRCH */
    case srch = 'SRCH';

    /** SNDX */
    case sndx = 'SNDX';

    /** T */
    case t = 'T';

    /** DN */
    case dn = 'DN';

    /** M */
    case m = 'M';

    /** NB */
    case nb = 'NB';

    /** UI */
    case ui = 'UI';

    /** SP */
    case sp = 'SP';

    /** Organizational unit/section/division name */
    case organizationalunitsectiondivisionname = 'ORGU';

    /** Service location name */
    case servicelocationname = 'ORGS';

    /** Business name */
    case businessname = 'ORGB';

    /** Locally used name */
    case locallyusedname = 'ORGL';

    /** Abbreviated name */
    case abbreviatedname = 'ORGA';

    /** Enterprise name */
    case enterprisename = 'ORGE';

    /** Other */
    case other = 'ORGX';

    /** Unknown */
    case unknown = 'ORGY';
}
