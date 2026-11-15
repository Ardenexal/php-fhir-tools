<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDAEntityCode
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAEntityCode
 * Version: 2.0.2-sd
 * Description: A value representing the specific kind of Entity the instance represents.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDAEntityCode', version: '2.0.2-sd')]
enum EntityCode: string
{
    /** BED */
    case bed = 'BED';

    /** BLDG */
    case bldg = 'BLDG';

    /** FLOOR */
    case floor = 'FLOOR';

    /** ROOM */
    case room = 'ROOM';

    /** WING */
    case wing = 'WING';

    /** HHOLD */
    case hhold = 'HHOLD';

    /** NAT */
    case nat = 'NAT';

    /** RELIG */
    case relig = 'RELIG';

    /** PRAC */
    case prac = 'PRAC';
}
