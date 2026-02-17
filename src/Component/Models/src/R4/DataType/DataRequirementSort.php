<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Specifies the order of the results to be returned.
 */
#[FHIRComplexType(typeName: 'DataRequirement.sort', fhirVersion: 'R4')]
class DataRequirementSort extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var StringPrimitive|string|null path The name of the attribute to perform the sort */
        #[NotBlank]
        public StringPrimitive|string|null $path = null,
        /** @var SortDirectionType|null direction ascending | descending */
        #[NotBlank]
        public ?SortDirectionType $direction = null,
    ) {
        parent::__construct($id, $extension);
    }
}
