<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ResourceVersionPolicy
 * URL: http://hl7.org/fhir/ValueSet/versioning-policy
 * Version: 4.0.1
 * Description: How the system supports versioning for a resource.
 */
enum ResourceVersionPolicy: string
{
    /** No VersionId Support */
    case noversionidsupport = 'no-version';

    /** Versioned */
    case versioned = 'versioned';

    /** VersionId tracked fully */
    case versionidtrackedfully = 'versioned-update';
}
