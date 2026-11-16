<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDAActSubstanceAdministrationCode
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAActSubstanceAdministrationCode
 * Version: 2.0.2-sd
 * Description: Describes the type of substance administration being performed.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDAActSubstanceAdministrationCode', version: '2.0.2-sd')]
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
