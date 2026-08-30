<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDAEntityNamePartQualifier
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAEntityNamePartQualifier
 * Version: 2.0.2-sd
 * Description: Qualifies parts of names
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDAEntityNamePartQualifier', version: '2.0.2-sd')]
enum EntityNamePartQualifier: string
{
    /** LS */
    case ls = 'LS';

    /** AC */
    case ac = 'AC';

    /** NB */
    case nb = 'NB';

    /** PR */
    case pr = 'PR';

    /** VV */
    case vv = 'VV';

    /** AD */
    case ad = 'AD';

    /** BR */
    case br = 'BR';

    /** SP */
    case sp = 'SP';

    /** CL */
    case cl = 'CL';

    /** IN */
    case in = 'IN';

    /** TITLE */
    case title = 'TITLE';
}
