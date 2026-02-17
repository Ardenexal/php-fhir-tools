<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Binds to a value set if this element is coded (code, Coding, CodeableConcept, Quantity), or the data types (string, uri).
 */
#[FHIRComplexType(typeName: 'ElementDefinition.binding', fhirVersion: 'R4')]
class ElementDefinitionBinding extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var BindingStrengthType|null strength required | extensible | preferred | example */
        #[NotBlank]
        public ?BindingStrengthType $strength = null,
        /** @var StringPrimitive|string|null description Human explanation of the value set */
        public StringPrimitive|string|null $description = null,
        /** @var CanonicalPrimitive|null valueSet Source of value set */
        public ?CanonicalPrimitive $valueSet = null,
    ) {
        parent::__construct($id, $extension);
    }
}
