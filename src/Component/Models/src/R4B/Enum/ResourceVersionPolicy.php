<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: ResourceVersionPolicy
 * URL: http://hl7.org/fhir/ValueSet/versioning-policy
 * Version: 4.3.0
 * Description: How the system supports versioning for a resource.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/versioning-policy', version: '4.3.0')]
enum ResourceVersionPolicy: string
{
    /** No VersionId Support */
    case noversionidsupport = 'no-version';

    /** Versioned */
    case versioned = 'versioned';

    /** VersionId tracked fully */
    case versionidtrackedfully = 'versioned-update';
}
