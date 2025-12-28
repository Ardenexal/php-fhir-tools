<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Entries in this list.
 */
#[FHIRBackboneElement(parentResource: 'List', elementPath: 'List.entry', fhirVersion: 'R4B')]
class FHIRListEntry extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null flag Status/Workflow information about this item */
        public ?\FHIRCodeableConcept $flag = null,
        /** @var FHIRBoolean|null deleted If this item is actually marked as deleted */
        public ?\FHIRBoolean $deleted = null,
        /** @var FHIRDateTime|null date When item added to list */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRReference|null item Actual entry */
        #[NotBlank]
        public ?\FHIRReference $item = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
