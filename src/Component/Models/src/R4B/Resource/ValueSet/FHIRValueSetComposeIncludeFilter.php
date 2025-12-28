<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Select concepts by specify a matching criterion based on the properties (including relationships) defined by the system, or on filters defined by the system. If multiple filters are specified, they SHALL all be true.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose.include.filter', fhirVersion: 'R4B')]
class FHIRValueSetComposeIncludeFilter extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null property A property/filter defined by the code system */
        #[NotBlank]
        public ?\FHIRCode $property = null,
        /** @var FHIRFilterOperatorType|null op = | is-a | descendent-of | is-not-a | regex | in | not-in | generalizes | exists */
        #[NotBlank]
        public ?\FHIRFilterOperatorType $op = null,
        /** @var FHIRString|string|null value Code from the system, or regex criteria, or boolean value for exists */
        #[NotBlank]
        public \FHIRString|string|null $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
