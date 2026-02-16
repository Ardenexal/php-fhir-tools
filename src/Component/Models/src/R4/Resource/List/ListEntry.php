<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\List;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Entries in this list.
 */
#[FHIRBackboneElement(parentResource: 'List', elementPath: 'List.entry', fhirVersion: 'R4')]
class ListEntry extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null flag Status/Workflow information about this item */
        public ?CodeableConcept $flag = null,
        /** @var bool|null deleted If this item is actually marked as deleted */
        public ?bool $deleted = null,
        /** @var DateTimePrimitive|null date When item added to list */
        public ?DateTimePrimitive $date = null,
        /** @var Reference|null item Actual entry */
        #[NotBlank]
        public ?Reference $item = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
