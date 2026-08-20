<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: StructureDefinitionKind
 * URL: http://hl7.org/fhir/ValueSet/structure-definition-kind
 * Version: 4.3.0
 * Description: Defines the type of structure that a definition is describing.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/structure-definition-kind', version: '4.3.0')]
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
