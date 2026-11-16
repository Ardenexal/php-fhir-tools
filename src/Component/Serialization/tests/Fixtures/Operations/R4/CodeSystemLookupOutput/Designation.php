<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4\CodeSystemLookupOutput;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;

/**
 * The `part[]` children of the R4 `$lookup` OUT `designation` group.
 *
 * Path-keyed: the class lives in a namespace named for its parent (`CodeSystemLookupOutput`), so
 * `designation` and `property` cannot collide even though both are OUT groups.
 *
 * Note what does NOT happen here: `designation` has a part named `use`, and the plan warns that a
 * class named `Use` would be a fatal parse error because `use` is a reserved word. It never arises —
 * `use` has no `part[]` of its own, so it is a property, not a class. Only part-bearing parameters
 * become classes. The reserved-word guard is still needed for operation *codes* and property names.
 */
final class Designation
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'language',
            phpName: 'language',
            use: 'out',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'The language this designation is defined for.',
        )]
        public readonly ?string $language = null,
        #[FhirOperationParameter(
            name: 'use',
            phpName: 'use',
            use: 'out',
            min: 0,
            max: '1',
            type: 'Coding',
            documentation: 'A code that details how this designation would be used.',
        )]
        public readonly ?Coding $use = null,
        #[FhirOperationParameter(
            name: 'value',
            phpName: 'value',
            use: 'out',
            min: 1,
            max: '1',
            type: 'string',
            documentation: 'The text value for this designation.',
        )]
        public readonly ?string $value = null,
    ) {
    }
}
