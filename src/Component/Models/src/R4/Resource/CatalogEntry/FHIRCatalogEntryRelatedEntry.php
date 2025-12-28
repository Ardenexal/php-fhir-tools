<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Used for example, to point to a substance, or to a device used to administer a medication.
 */
#[FHIRBackboneElement(parentResource: 'CatalogEntry', elementPath: 'CatalogEntry.relatedEntry', fhirVersion: 'R4')]
class FHIRCatalogEntryRelatedEntry extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCatalogEntryRelationTypeType|null relationtype triggers | is-replaced-by */
        #[NotBlank]
        public ?FHIRCatalogEntryRelationTypeType $relationtype = null,
        /** @var FHIRReference|null item The reference to the related item */
        #[NotBlank]
        public ?FHIRReference $item = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
