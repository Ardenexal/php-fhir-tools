<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies other resource parameters within the operation invocation that are expected to resolve to this resource.
 */
#[FHIRBackboneElement(parentResource: 'OperationDefinition', elementPath: 'OperationDefinition.parameter.referencedFrom', fhirVersion: 'R5')]
class FHIROperationDefinitionParameterReferencedFrom extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
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
        public \FHIRString|string|null $source = null,
        /** @var FHIRString|string|null sourceId Element id of reference */
        public \FHIRString|string|null $sourceId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
