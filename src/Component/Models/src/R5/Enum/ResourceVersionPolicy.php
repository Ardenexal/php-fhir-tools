<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Resource Version Policy
 * URL: http://hl7.org/fhir/ValueSet/versioning-policy
 * Version: 5.0.0
 * Description: How the system supports versioning for a resource.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/versioning-policy', version: '5.0.0')]
enum ResourceVersionPolicy: string
{
    /** No VersionId Support */
    case noversionidsupport = 'no-version';

    /** Versioned */
    case versioned = 'versioned';

    /** VersionId tracked fully */
    case versionidtrackedfully = 'versioned-update';
}
