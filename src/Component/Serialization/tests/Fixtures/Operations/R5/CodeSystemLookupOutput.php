<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5\CodeSystemLookupOutput\Designation;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5\CodeSystemLookupOutput\Property;

/**
 * Hand-written stand-in for the generated R5 `CodeSystem/$lookup` OUT class.
 *
 * `$lookup` is output-shape **Parameters**: it returns `name`, `display`, `designation[]` and
 * `property[]` alongside one another, which only a `Parameters` resource can carry. That makes it
 * the minority shape — roughly a quarter of core operations — and the reason a class-B fixture is
 * also required before the mapper can be trusted.
 *
 * `version` appears here as an OUT parameter and again on {@see CodeSystemLookupInput} as an IN
 * parameter. Two `use` values, one wire name: this is why IN and OUT are separate classes rather
 * than one flat class keyed by parameter name.
 */
final class CodeSystemLookupOutput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'name',
            phpName: 'name',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'A display name for the code system.',
        )]
        public readonly ?string $name = null,
        #[FhirOperationParameter(
            name: 'version',
            phpName: 'version',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The version that these details are based on.',
        )]
        public readonly ?string $version = null,
        #[FhirOperationParameter(
            name: 'display',
            phpName: 'display',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'The preferred display for this concept.',
        )]
        public readonly ?string $display = null,
        /** R5-only. */
        #[FhirOperationParameter(
            name: 'definition',
            phpName: 'definition',
            use: 'out',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'A statement of the meaning of the concept from the code system.',
        )]
        public readonly ?string $definition = null,
        /**
         * @var list<Designation>
         */
        #[FhirOperationParameter(
            name: 'designation',
            phpName: 'designation',
            use: 'out',
            min: 0,
            max: '*',
            partClass: Designation::class,
            documentation: 'Additional representations for this concept.',
        )]
        public readonly array $designation = [],
        /**
         * @var list<Property>
         */
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'out',
            min: 0,
            max: '*',
            partClass: Property::class,
            documentation: 'One property value for this concept.',
        )]
        public readonly array $property = [],
    ) {
    }
}
