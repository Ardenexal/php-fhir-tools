<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Attribute\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;

/**
 * The `part[]` children of {@see LookupOutputFixture}'s `property` group.
 *
 * `value` here is polymorphic and carries the seven-variant set AllowedTypeReader resolves for
 * CodeSystem/$lookup. The variant shape is deliberately identical to FhirProperty::$variants and
 * PropertyVariantMetadata — operations reuse the existing choice machinery rather than a parallel one.
 *
 * Note `phpType` holds a fully-qualified class name for complex types and a PHP builtin name for
 * scalars, matching what FHIRExtensionGenerator emits (`ltrim($phpType, '\\')` on a resolved FQCN).
 * A bare 'Coding' here would read as a correct example and produce a class name the mapper cannot
 * instantiate.
 */
final class LookupOutputPropertyFixture
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'code',
            phpName: 'code',
            use: 'out',
            min: 1,
            max: '1',
            type: 'code',
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
                ['fhirType' => 'Coding', 'propertyKind' => 'complex', 'phpType' => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding', 'jsonKey' => 'valueCoding'],
                ['fhirType' => 'boolean', 'propertyKind' => 'scalar', 'phpType' => 'bool', 'jsonKey' => 'valueBoolean'],
                ['fhirType' => 'code', 'propertyKind' => 'primitive', 'phpType' => 'string', 'jsonKey' => 'valueCode'],
                ['fhirType' => 'dateTime', 'propertyKind' => 'primitive', 'phpType' => 'string', 'jsonKey' => 'valueDateTime'],
                ['fhirType' => 'decimal', 'propertyKind' => 'scalar', 'phpType' => 'float', 'jsonKey' => 'valueDecimal'],
                ['fhirType' => 'integer', 'propertyKind' => 'scalar', 'phpType' => 'int', 'jsonKey' => 'valueInteger'],
                ['fhirType' => 'string', 'propertyKind' => 'primitive', 'phpType' => 'string', 'jsonKey' => 'valueString'],
            ],
        )]
        public readonly mixed $value = null,
    ) {
    }
}
