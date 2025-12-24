<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Binds to a value set if this parameter is coded (code, Coding, CodeableConcept).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter.binding', fhirVersion: 'R5')]
class FHIROperationDefinitionParameterBinding extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBindingStrengthType|null strength required | extensible | preferred | example */
        #[NotBlank]
        public ?FHIRBindingStrengthType $strength = null,
        /** @var FHIRCanonical|null valueSet Source of value set */
        #[NotBlank]
        public ?FHIRCanonical $valueSet = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
