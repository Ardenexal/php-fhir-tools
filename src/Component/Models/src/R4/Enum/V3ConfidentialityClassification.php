<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: V3 Value SetConfidentialityClassification
 * URL: http://terminology.hl7.org/ValueSet/v3-ConfidentialityClassification
 * Version: 2014-03-26
 * Description:  Set of codes used to value Act.Confidentiality and Role.Confidentiality attribute in accordance with the definition for concept domain "Confidentiality".
 */
enum V3ConfidentialityClassification: string
{
    /** Confidentiality */
    case confidentiality = '_Confidentiality';

    /** ConfidentialityByAccessKind */
    case confidentialitybyaccesskind = '_ConfidentialityByAccessKind';

    /** ConfidentialityByInfoType */
    case confidentialitybyinfotype = '_ConfidentialityByInfoType';

    /** ConfidentialityModifiers */
    case confidentialitymodifiers = '_ConfidentialityModifiers';

    /** U */
    case u = 'U';

    /** L */
    case l = 'L';

    /** M */
    case m = 'M';

    /** N */
    case n = 'N';

    /** R */
    case r = 'R';

    /** V */
    case v = 'V';
}
