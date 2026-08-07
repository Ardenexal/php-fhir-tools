<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemLookup;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup',
    use: 'out',
    version: 'R4',
    operation: 'CodeSystemLookup',
    path: 'property',
)]
final class CodeSystemLookupOutProperty
{
    /**
     * @param list<CodeSystemLookupOutPropertySubproperty> $subproperty
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'out',
            min: 1,
            max: '1',
            type: 'code',
            documentation: 'Identifies the property returned',
        )]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'value',
            phpName: 'value',
            use: 'out',
            min: 0,
            max: '1',
            type: 'Element',
            variants: [
                [
                    'fhirType'     => 'code',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive',
                    'jsonKey'      => 'valueCode',
                    'isBuiltin'    => false,
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding',
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
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive',
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
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
                    'jsonKey'      => 'valueString',
                    'isBuiltin'    => false,
                ],
            ],
            documentation: 'The value of the property returned',
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
        #[FhirOperationParameter(
            name: 'subproperty',
            phpName: 'subproperty',
            use: 'out',
            min: 0,
            max: '*',
            partClass: CodeSystemLookupOutPropertySubproperty::class,
            documentation: 'Nested Properties (mainly used for SNOMED CT decomposition, for relationship Groups)',
        )]
        public readonly array $subproperty = [],
    ) {
    }
}
