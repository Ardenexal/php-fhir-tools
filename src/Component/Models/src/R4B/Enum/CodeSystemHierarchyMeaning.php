<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CodeSystemHierarchyMeaning
 * URL: http://hl7.org/fhir/ValueSet/codesystem-hierarchy-meaning
 * Version: 4.3.0
 * Description: The meaning of the hierarchy of concepts in a code system.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/codesystem-hierarchy-meaning', version: '4.3.0')]
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
