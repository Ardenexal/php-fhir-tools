<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Linkage;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\LinkageTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies which record considered as the reference to the same real-world occurrence as well as how the items should be evaluated within the collection of linked items.
 */
#[FHIRBackboneElement(parentResource: 'Linkage', elementPath: 'Linkage.item', fhirVersion: 'R4')]
class LinkageItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var LinkageTypeType|null type source | alternate | historical */
        #[NotBlank]
        public ?LinkageTypeType $type = null,
        /** @var Reference|null resource Resource being linked */
        #[NotBlank]
        public ?Reference $resource = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
