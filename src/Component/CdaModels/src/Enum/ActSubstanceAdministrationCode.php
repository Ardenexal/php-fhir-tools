<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAActSubstanceAdministrationCode
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAActSubstanceAdministrationCode
 * Version: 2.0.2-sd
 * Description: Describes the type of substance administration being performed.
 */
enum ActSubstanceAdministrationCode: string
{
    /** DRUG */
    case drug = 'DRUG';

    /** FD */
    case fd = 'FD';

    /** IMMUNIZ */
    case immuniz = 'IMMUNIZ';

    /** BOOSTER */
    case booster = 'BOOSTER';

    /** INITIMMUNIZ */
    case initimmuniz = 'INITIMMUNIZ';
}
