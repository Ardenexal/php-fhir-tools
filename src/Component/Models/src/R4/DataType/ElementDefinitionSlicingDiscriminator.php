<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Designates which child elements are used to discriminate between the slices when processing an instance. If one or more discriminators are provided, the value of the child elements in the instance data SHALL completely distinguish which slice the element in the resource matches based on the allowed values for those elements in each of the slices.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.slicing.discriminator', fhirVersion: 'R4')]
class ElementDefinitionSlicingDiscriminator extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var DiscriminatorTypeType|null type value | exists | pattern | type | profile */
        #[NotBlank]
        public ?DiscriminatorTypeType $type = null,
        /** @var StringPrimitive|string|null path Path to element value */
        #[NotBlank]
        public StringPrimitive|string|null $path = null,
    ) {
        parent::__construct($id, $extension);
    }
}
