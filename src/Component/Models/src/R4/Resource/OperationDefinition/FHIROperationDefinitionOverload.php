<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Defines an appropriate combination of parameters to use when invoking this operation, to help code generators when generating overloaded parameter sets for this operation.
 */
#[FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.overload', fhirVersion: 'R4')]
class FHIROperationDefinitionOverload extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRString|string> parameterName Name of parameter to include in overload */
        public array $parameterName = [],
        /** @var FHIRString|string|null comment Comments to go on overload */
        public FHIRString|string|null $comment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
