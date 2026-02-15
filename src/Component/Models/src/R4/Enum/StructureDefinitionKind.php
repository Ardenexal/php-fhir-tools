<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: StructureDefinitionKind
 * URL: http://hl7.org/fhir/ValueSet/structure-definition-kind
 * Version: 4.0.1
 * Description: Defines the type of structure that a definition is describing.
 */
enum StructureDefinitionKind: string
{
    /** Primitive Data Type */
    case primitivedatatype = 'primitive-type';

    /** Complex Data Type */
    case complexdatatype = 'complex-type';

    /** Resource */
    case resource = 'resource';

    /** Logical */
    case logical = 'logical';
}
