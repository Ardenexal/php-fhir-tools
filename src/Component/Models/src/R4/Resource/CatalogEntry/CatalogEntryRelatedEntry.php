<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CatalogEntry;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CatalogEntryRelationTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Used for example, to point to a substance, or to a device used to administer a medication.
 */
#[FHIRBackboneElement(parentResource: 'CatalogEntry', elementPath: 'CatalogEntry.relatedEntry', fhirVersion: 'R4')]
class CatalogEntryRelatedEntry extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CatalogEntryRelationTypeType|null relationtype triggers | is-replaced-by */
        #[NotBlank]
        public ?CatalogEntryRelationTypeType $relationtype = null,
        /** @var Reference|null item The reference to the related item */
        #[NotBlank]
        public ?Reference $item = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
