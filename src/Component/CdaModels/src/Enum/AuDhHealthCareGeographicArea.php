<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: Health Care Geographic Area
 * URL: http://ns.electronichealth.net.au/cda/ValueSet/dh-HealthCareGeographicArea
 * Version: 1.0.1
 * Description: Health Care Client Identifier Geographic Area
 */
enum AuDhHealthCareGeographicArea: string
{
    /** Local Client (Unit Record) Identifier */
    case localclientunitrecordidentifier = 'Local Client (Unit Record) Identifier';

    /** Area/Region/District Identifier */
    case arearegiondistrictidentifier = 'Area/Region/District Identifier';

    /** State or Territory Identifier */
    case stateorterritoryidentifier = 'State or Territory Identifier';

    /** National Identifier */
    case nationalidentifier = 'National Identifier';
}
