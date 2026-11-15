<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: Filter Operator
 * URL: http://hl7.org/fhir/ValueSet/filter-operator
 * Version: 5.0.0
 * Description: The kind of operation to perform as a part of a property based filter.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/filter-operator', version: '5.0.0')]
enum FilterOperator: string
{
    /** Equals */
    case equals = '=';

    /** Is A (by subsumption) */
    case i_sa_bysubsumption = 'is-a';

    /** Descendent Of (by subsumption) */
    case descendentofbysubsumption = 'descendent-of';

    /** Not (Is A) (by subsumption) */
    case noti_sa_bysubsumption = 'is-not-a';

    /** Regular Expression */
    case regularexpression = 'regex';

    /** In Set */
    case inset = 'in';

    /** Not in Set */
    case notinset = 'not-in';

    /** Generalizes (by Subsumption) */
    case generalizesbysubsumption = 'generalizes';

    /** Child Of */
    case childof = 'child-of';

    /** Descendent Leaf */
    case descendentleaf = 'descendent-leaf';

    /** Exists */
    case exists = 'exists';
}
