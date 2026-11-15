<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Attribute\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;

/**
 * A generated operation OUT class, mirroring the real R5 CodeSystem/$lookup output shape.
 *
 * Faithful to the published definition: `name` is `1..1 string` and `property` is `0..* ` with no
 * `type` of its own (it is a pure `part` group). Trimmed to the two parameters that carry the
 * behaviour under test — the real definition also declares `version`, `display`, `definition` and
 * `designation`.
 *
 * `property` here is the `use: out` backbone group — the same wire name as the `use: in` code
 * parameter on {@see AwkwardNamesInputFixture}, which is the collision that forces path-keyed
 * nested classes and a $partClass pointer rather than name-based lookup.
 */
final class LookupOutputFixture
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'name',
            phpName: 'name',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
        )]
        public readonly ?string $name = null,
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'out',
            min: 0,
            max: '*',
            partClass: LookupOutputPropertyFixture::class,
        )]
        public readonly array $property = [],
    ) {
    }
}
