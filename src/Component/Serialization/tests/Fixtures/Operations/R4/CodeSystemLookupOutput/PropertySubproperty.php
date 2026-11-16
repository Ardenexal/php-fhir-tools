<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\CodeSystemLookupOutput;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;

/**
 * The `part[]` children of `property.subproperty` in the R4 `$lookup` output.
 *
 * The class name is keyed by parameter path (`property` + `subproperty`), not by the parameter's own
 * name. Keying by name alone would collide with {@see Property}'s own `value`, and both `value`
 * parameters carry the same seven-variant choice while differing in cardinality — `property.value`
 * is `0..1` but `property.subproperty.value` is `1..1`. A name-keyed scheme would silently merge them.
 */
final class PropertySubproperty
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'out',
            min: 1,
            max: '1',
            type: 'code',
            documentation: 'The sub-property code.',
        )]
        public readonly ?string $code = null,
        /**
         * `min: 1` here, unlike `property.value` which is `0..1` — the one genuine shape difference
         * between the two levels.
         */
        #[FhirOperationParameter(
            name: 'value',
            phpName: 'value',
            use: 'out',
            min: 1,
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
            documentation: 'The value of the sub-property.',
        )]
        public readonly mixed $value = null,
        #[FhirOperationParameter(
            name: 'description',
            phpName: 'description',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'Human-readable representation of the sub-property value.',
        )]
        public readonly ?string $description = null,
    ) {
    }
}
