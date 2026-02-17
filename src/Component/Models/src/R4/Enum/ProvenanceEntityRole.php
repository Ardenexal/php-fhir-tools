<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: ProvenanceEntityRole
 * URL: http://hl7.org/fhir/ValueSet/provenance-entity-role
 * Version: 4.0.1
 * Description: How an entity was used in an activity.
 */
enum ProvenanceEntityRole: string
{
    /** Derivation */
    case derivation = 'derivation';
}
