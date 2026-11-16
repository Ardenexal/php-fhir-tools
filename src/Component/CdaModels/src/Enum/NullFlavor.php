<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDANullFlavor
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDANullFlavor
 * Version: 2.0.2-sd
 * Description: CDA NullFlavors - limited to values allowed in original CDA definition
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDANullFlavor', version: '2.0.2-sd')]
enum NullFlavor: string
{
    /** NP */
    case np = 'NP';

    /** NI */
    case ni = 'NI';

    /** MSK */
    case msk = 'MSK';

    /** NA */
    case na = 'NA';

    /** OTH */
    case oth = 'OTH';

    /** NINF */
    case ninf = 'NINF';

    /** PINF */
    case pinf = 'PINF';

    /** UNK */
    case unk = 'UNK';

    /** NASK */
    case nask = 'NASK';

    /** TRC */
    case trc = 'TRC';

    /** ASKU */
    case asku = 'ASKU';

    /** NAV */
    case nav = 'NAV';
}
