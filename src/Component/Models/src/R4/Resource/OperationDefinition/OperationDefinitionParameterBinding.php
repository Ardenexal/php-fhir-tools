<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BindingStrengthType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Binds to a value set if this parameter is coded (code, Coding, CodeableConcept).
 */
#[FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter.binding', fhirVersion: 'R4')]
class OperationDefinitionParameterBinding extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var BindingStrengthType|null strength required | extensible | preferred | example */
        #[NotBlank]
        public ?BindingStrengthType $strength = null,
        /** @var CanonicalPrimitive|null valueSet Source of value set */
        #[NotBlank]
        public ?CanonicalPrimitive $valueSet = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
