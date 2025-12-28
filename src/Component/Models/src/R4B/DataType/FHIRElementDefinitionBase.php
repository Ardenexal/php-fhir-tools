<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the base definition of the element, provided to make it unnecessary for tools to trace the deviation of the element through the derived and related profiles. When the element definition is not the original definition of an element - i.g. either in a constraint on another type, or for elements from a super type in a snap shot - then the information in provided in the element definition may be different to the base definition. On the original definition of the element, it will be same.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.base', fhirVersion: 'R4B')]
class FHIRElementDefinitionBase extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null path Path that identifies the base element */
        #[NotBlank]
        public FHIRString|string|null $path = null,
        /** @var FHIRUnsignedInt|null min Min cardinality of the base element */
        #[NotBlank]
        public ?FHIRUnsignedInt $min = null,
        /** @var FHIRString|string|null max Max cardinality of the base element */
        #[NotBlank]
        public FHIRString|string|null $max = null,
    ) {
        parent::__construct($id, $extension);
    }
}
