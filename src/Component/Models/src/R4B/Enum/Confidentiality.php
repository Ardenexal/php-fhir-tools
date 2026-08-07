<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: Confidentiality
 * URL: http://terminology.hl7.org/ValueSet/v3-Confidentiality
 * Version: 3.0.0
 * Description: Set of codes used to value Act.Confidentiality and Role.Confidentiality attribute in accordance with the definition for concept domain "Confidentiality".
 */
enum Confidentiality: string
{
    /** Confidentiality */
    case confidentiality = '_Confidentiality';

    /** low */
    case low = 'L';

    /** moderate */
    case moderate = 'M';

    /** normal */
    case normal = 'N';

    /** restricted */
    case restricted = 'R';

    /** unrestricted */
    case unrestricted = 'U';

    /** very restricted */
    case veryrestricted = 'V';

    /** ConfidentialityByAccessKind */
    case confidentialitybyaccesskind = '_ConfidentialityByAccessKind';

    /** business */
    case business = 'B';

    /** clinician */
    case clinician = 'D';

    /** individual */
    case individual = 'I';

    /** ConfidentialityByInfoType */
    case confidentialitybyinfotype = '_ConfidentialityByInfoType';

    /** substance abuse related */
    case substanceabuserelated = 'ETH';

    /** HIV related */
    case hivrelated = 'HIV';

    /** psychiatry relate */
    case psychiatryrelate = 'PSY';

    /** sexual and domestic violence related */
    case sexualanddomesticviolencerelated = 'SDV';

    /** ConfidentialityModifiers */
    case confidentialitymodifiers = '_ConfidentialityModifiers';

    /** celebrity */
    case celebrity = 'C';

    /** sensitive */
    case sensitive = 'S';

    /** taboo */
    case taboo = 'T';
}
