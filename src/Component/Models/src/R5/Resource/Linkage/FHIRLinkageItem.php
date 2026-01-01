<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRLinkageTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies which record considered as the reference to the same real-world occurrence as well as how the items should be evaluated within the collection of linked items.
 */
#[FHIRBackboneElement(parentResource: 'Linkage', elementPath: 'Linkage.item', fhirVersion: 'R5')]
class FHIRLinkageItem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRLinkageTypeType|null type source | alternate | historical */
        #[NotBlank]
        public ?FHIRLinkageTypeType $type = null,
        /** @var FHIRReference|null resource Resource being linked */
        #[NotBlank]
        public ?FHIRReference $resource = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
