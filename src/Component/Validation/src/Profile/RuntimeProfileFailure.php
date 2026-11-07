<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * Why a profile URL could not be resolved into rules for runtime validation.
 *
 * @author Ardenexal
 */
enum RuntimeProfileFailure: string
{
    /** No registry holds a StructureDefinition with this URL, for any FHIR release. */
    case Unknown = 'unknown';

    /** The URL is known, but only for other FHIR releases than the resource's. */
    case FhirVersionMismatch = 'fhir-version-mismatch';

    /** The profile, or one of its constraint ancestors, has no snapshot. Snapshots are not generated. */
    case NoSnapshot = 'no-snapshot';

    /** A `baseDefinition` in the chain names a StructureDefinition no registry holds. */
    case UnresolvedParent = 'unresolved-parent';

    /** The definition is not a resource, complex-type or Extension definition (e.g. a primitive or logical model). */
    case UnsupportedKind = 'unsupported-kind';

    /** Following `baseDefinition` leads back to a definition already in the chain. */
    case CircularParent = 'circular-parent';
}
