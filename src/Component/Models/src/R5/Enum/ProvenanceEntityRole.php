<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Provenance Entity Role
 * URL: http://hl7.org/fhir/ValueSet/provenance-entity-role
 * Version: 5.0.0
 * Description: How an entity was used in an activity.
 */
enum ProvenanceEntityRole: string
{
    /** Revision */
    case revision = 'revision';

    /** Quotation */
    case quotation = 'quotation';

    /** Source */
    case source = 'source';

    /** Instantiates */
    case instantiates = 'instantiates';

    /** Removal */
    case removal = 'removal';
}
