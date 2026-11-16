<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\CodeSystemLookupOutput;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;

/**
 * The `part[]` children of the R4 `$lookup` OUT `property` group.
 *
 * This is the OUT `property` — the same wire name as the IN `property`, which is a plain
 * `0..* code`. They share nothing but the name.
 *
 * `subproperty` recurses into {@see PropertySubproperty}, which is where the path-keyed naming earns
 * its keep: both levels declare `code`, `value` and `description`.
 */
final class Property
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'out',
            min: 1,
            max: '1',
            type: 'code',
            documentation: 'Identifies the property returned.',
        )]
        public readonly ?string $code = null,
        /**
         * The polymorphic parameter this milestone exists to prove. Seven allowed types, none of
         * them declared in `parameter.allowedType` — every one comes from the legacy
         * `operationdefinition-allowed-type` extension, on R4 and R5 alike.
         */
        #[FhirOperationParameter(
            name: 'value',
            phpName: 'value',
            use: 'out',
            min: 0,
            max: '1',
            type: 'Element',
            variants: [
                ['fhirType' => 'Coding', 'propertyKind' => 'complex', 'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding', 'jsonKey' => 'valueCoding'],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'valueBoolean'],
                ['fhirType' => 'code', 'propertyKind' => 'primitive', 'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive', 'jsonKey' => 'valueCode'],
                ['fhirType' => 'dateTime', 'propertyKind' => 'primitive', 'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive', 'jsonKey' => 'valueDateTime'],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'string', 'jsonKey' => 'valueDecimal'],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
                ['fhirType' => 'string', 'propertyKind' => 'primitive', 'phpType' => 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive', 'jsonKey' => 'valueString'],
            ],
            documentation: 'The value of the property returned.',
        )]
        public readonly mixed $value = null,
        #[FhirOperationParameter(
            name: 'description',
            phpName: 'description',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'Human-readable representation of the property value.',
        )]
        public readonly ?string $description = null,
        /**
         * @var list<PropertySubproperty>
         */
        #[FhirOperationParameter(
            name: 'subproperty',
            phpName: 'subproperty',
            use: 'out',
            min: 0,
            max: '*',
            partClass: PropertySubproperty::class,
            documentation: 'Nested Properties (mainly used for SNOMED CT decomposition, for relationship Groups).',
        )]
        public readonly array $subproperty = [],
    ) {
    }
}
