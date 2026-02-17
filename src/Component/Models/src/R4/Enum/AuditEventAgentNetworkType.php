<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: AuditEventAgentNetworkType
 * URL: http://hl7.org/fhir/ValueSet/network-type
 * Version: 4.0.1
 * Description: The type of network access point of this agent in the audit event.
 */
enum AuditEventAgentNetworkType: string
{
    /** Machine Name */
    case machinename = '1';

    /** IP Address */
    case ipaddress = '2';

    /** Telephone Number */
    case telephonenumber = '3';

    /** Email address */
    case emailaddress = '4';

    /** URI */
    case uri = '5';
}
