<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\CodeSystemFindMatches;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/CodeSystem-find-matches',
    use: 'in',
    version: 'R4',
    operation: 'CodeSystemFindMatches',
    path: 'property',
)]
final class CodeSystemFindMatchesInProperty
{
    /**
     * @param list<CodeSystemFindMatchesInPropertySubproperty> $subproperty
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'in',
            min: 1,
            max: '1',
            type: 'code',
            documentation: 'Identifies the property provided',
        )]
        public readonly ?string $code = null,
        #[FhirOperationParameter(
            name: 'value',
            phpName: 'value',
            use: 'in',
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
            documentation: 'The value of the property provided',
        )]
        public readonly mixed $value = null,
        #[FhirOperationParameter(
            name: 'subproperty',
            phpName: 'subproperty',
            use: 'in',
            min: 0,
            max: '*',
            partClass: CodeSystemFindMatchesInPropertySubproperty::class,
            documentation: 'Nested Properties (mainly used for SNOMED CT composition, for relationship Groups)',
        )]
        public readonly array $subproperty = [],
    ) {
    }
}
