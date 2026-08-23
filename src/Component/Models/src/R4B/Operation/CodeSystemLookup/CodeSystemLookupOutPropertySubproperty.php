<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\CodeSystemLookup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    use: 'out',
    version: 'R4B',
    operation: 'CodeSystemLookup',
    path: 'property.subproperty',
)]
final class CodeSystemLookupOutPropertySubproperty
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'out',
            min: 1,
            max: '1',
            type: 'code',
            documentation: 'Identifies the sub-property returned',
        )]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'value',
            phpName: 'value',
            use: 'out',
            min: 1,
            max: '1',
            type: 'Element',
            variants: [
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive',
                    'jsonKey'      => 'valueCode',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding',
                    'jsonKey'      => 'valueCoding',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'boolean',
                    'propertyKind' => 'scalar',
                    'phpType'      => 'bool',
                    'jsonKey'      => 'valueBoolean',
                    'isBuiltin'    => true,
                ],
                [
                    'fhirType'     => 'dateTime',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive',
                    'jsonKey'      => 'valueDateTime',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'decimal',
                    'propertyKind' => 'scalar',
                    'phpType'      => 'string',
                    'jsonKey'      => 'valueDecimal',
                    'isBuiltin'    => true,
                ],
                [
                    'fhirType'     => 'integer',
                    'propertyKind' => 'scalar',
                    'phpType'      => 'int',
                    'jsonKey'      => 'valueInteger',
                    'isBuiltin'    => true,
                ],
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                    'isBuiltin'    => false,
                ],
            ],
            documentation: 'The value of the sub-property returned',
        )]
        public readonly mixed $value = null,
        #[FhirOperationParameter(
            name: 'description',
            phpName: 'description',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'Human Readable representation of the property value (e.g. display for a code)',
        )]
        public readonly ?string $description = null,
    ) {
    }
}
