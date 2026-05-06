<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Structure Definition Kind
 * URL: http://hl7.org/fhir/ValueSet/structure-definition-kind
 * Version: 5.0.0
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
