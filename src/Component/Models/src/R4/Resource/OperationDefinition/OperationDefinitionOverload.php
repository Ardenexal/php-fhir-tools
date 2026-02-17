<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Defines an appropriate combination of parameters to use when invoking this operation, to help code generators when generating overloaded parameter sets for this operation.
 */
#[FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.overload', fhirVersion: 'R4')]
class OperationDefinitionOverload extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<StringPrimitive|string> parameterName Name of parameter to include in overload */
        public array $parameterName = [],
        /** @var StringPrimitive|string|null comment Comments to go on overload */
        public StringPrimitive|string|null $comment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
