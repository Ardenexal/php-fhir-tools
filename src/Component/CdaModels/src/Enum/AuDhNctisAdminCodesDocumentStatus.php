<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: NCTIS Admin Codes Document Status
 * URL: http://ns.electronichealth.net.au/cda/ValueSet/dh-NctisAdminCodesDocumentStatus
 * Version: 1.0.1
 * Description: NCTIS Admin Codes Document Status
 */
#[FHIRValueSetSource(url: 'http://ns.electronichealth.net.au/cda/ValueSet/dh-NctisAdminCodesDocumentStatus', version: '1.0.1')]
enum AuDhNctisAdminCodesDocumentStatus: string
{
    /** Interim */
    case interim = 'I';

    /** Final */
    case final = 'F';

    /** Withdrawn */
    case withdrawn = 'W';
}
