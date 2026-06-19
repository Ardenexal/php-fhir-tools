<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDAInformationRecipientRole
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAInformationRecipientRole
 * Version: 2.0.2-sd
 * Description: Used to represent the role(s) of those who should receive a copy of a document - limited to values allowed in original CDA definition
 */
enum InformationRecipientRole: string
{
    /** ASSIGNED */
    case assigned = 'ASSIGNED';

    /** HLTHCHRT */
    case hlthchrt = 'HLTHCHRT';
}
