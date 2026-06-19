<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDASetOperator
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDASetOperator
 * Version: 2.0.2-sd
 * Description: Determins the intersectionality of multiple sets
 */
enum SetOperator: string
{
    /** A */
    case a = 'A';

    /** E */
    case e = 'E';

    /** H */
    case h = 'H';

    /** I */
    case i = 'I';

    /** P */
    case p = 'P';
}
