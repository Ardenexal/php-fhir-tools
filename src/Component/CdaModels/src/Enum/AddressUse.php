<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAAddressUse
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAAddressUse
 * Version: 1.0.1
 * Description: Codes that provide guidance around the circumstances in which a given address should be used - limited to values allowed in original CDA definition
 */
enum AddressUse: string
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

    /** AS */
    case as = 'AS';

    /** EC */
    case ec = 'EC';

    /** MC */
    case mc = 'MC';

    /** PG */
    case pg = 'PG';
}
