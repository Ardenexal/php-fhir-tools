<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies other resource parameters within the operation invocation that are expected to resolve to this resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter.referencedFrom', fhirVersion: 'R4')]
class FHIROperationDefinitionParameterReferencedFrom extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null source Referencing parameter */
        #[NotBlank]
        public FHIRString|string|null $source = null,
        /** @var FHIRString|string|null sourceId Element id of reference */
        public FHIRString|string|null $sourceId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
