<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: ProvenanceEntityRole
 * URL: http://hl7.org/fhir/ValueSet/provenance-entity-role
 * Version: 4.3.0
 * Description: How an entity was used in an activity.
 */
enum ProvenanceEntityRole: string
{
    /** Derivation */
    case derivation = 'derivation';

    /** Revision */
    case revision = 'revision';

    /** Quotation */
    case quotation = 'quotation';

    /** Source */
    case source = 'source';

    /** Removal */
    case removal = 'removal';
}
