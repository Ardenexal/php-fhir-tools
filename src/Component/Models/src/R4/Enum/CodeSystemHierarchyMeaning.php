<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CodeSystemHierarchyMeaning
 * URL: http://hl7.org/fhir/ValueSet/codesystem-hierarchy-meaning
 * Version: 4.0.1
 * Description: The meaning of the hierarchy of concepts in a code system.
 */
enum CodeSystemHierarchyMeaning: string
{
    /** Grouped By */
    case groupedby = 'grouped-by';

    /** Is-A */
    case i_sa = 'is-a';

    /** Part Of */
    case partof = 'part-of';

    /** Classified With */
    case classifiedwith = 'classified-with';
}
