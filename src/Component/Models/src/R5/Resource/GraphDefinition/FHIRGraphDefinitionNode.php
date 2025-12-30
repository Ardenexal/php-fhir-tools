<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVersionIndependentResourceTypesAllType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Potential target for the link.
 */
#[FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.node', fhirVersion: 'R5')]
class FHIRGraphDefinitionNode extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null nodeId Internal ID - target for link references */
        #[NotBlank]
        public ?FHIRId $nodeId = null,
        /** @var FHIRString|string|null description Why this node is specified */
        public FHIRString|string|null $description = null,
        /** @var FHIRVersionIndependentResourceTypesAllType|null type Type of resource this link refers to */
        #[NotBlank]
        public ?FHIRVersionIndependentResourceTypesAllType $type = null,
        /** @var FHIRCanonical|null profile Profile for the target resource */
        public ?FHIRCanonical $profile = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
