<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDAPostalAddressUse
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAPostalAddressUse
 * Version: 2.0.2-sd
 * Description: A set of codes advising a system or user which address in a set of like addresses to select for a given purpose - limited to values allowed in original CDA definition
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDAPostalAddressUse', version: '2.0.2-sd')]
enum PostalAddressUse: string
{
    /** H */
    case h = 'H';

    /** HP */
    case hp = 'HP';

    /** HV */
    case hv = 'HV';

    /** WP */
    case wp = 'WP';

    /** DIR */
    case dir = 'DIR';

    /** PUB */
    case pub = 'PUB';

    /** BAD */
    case bad = 'BAD';

    /** TMP */
    case tmp = 'TMP';

    /** PHYS */
    case phys = 'PHYS';

    /** PST */
    case pst = 'PST';

    /** ABC */
    case abc = 'ABC';

    /** IDE */
    case ide = 'IDE';

    /** SYL */
    case syl = 'SYL';
}
