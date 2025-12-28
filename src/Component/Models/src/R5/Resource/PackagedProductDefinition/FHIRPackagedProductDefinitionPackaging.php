<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A packaging item, as a container for medically related items, possibly with other packaging items within, or a packaging component, such as bottle cap (which is not a device or a medication manufactured item).
 */
#[FHIRBackboneElement(parentResource: 'PackagedProductDefinition', elementPath: 'PackagedProductDefinition.packaging', fhirVersion: 'R5')]
class FHIRPackagedProductDefinitionPackaging extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier An identifier that is specific to this particular part of the packaging. Including possibly a Data Carrier Identifier */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null type The physical type of the container of the items */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRBoolean|null componentPart Is this a part of the packaging (e.g. a cap or bottle stopper), rather than the packaging itself (e.g. a bottle or vial) */
        public ?\FHIRBoolean $componentPart = null,
        /** @var FHIRInteger|null quantity The quantity of this level of packaging in the package that contains it (with the outermost level being 1) */
        public ?\FHIRInteger $quantity = null,
        /** @var array<FHIRCodeableConcept> material Material type of the package item */
        public array $material = [],
        /** @var array<FHIRCodeableConcept> alternateMaterial A possible alternate material for this part of the packaging, that is allowed to be used instead of the usual material */
        public array $alternateMaterial = [],
        /** @var array<FHIRProductShelfLife> shelfLifeStorage Shelf Life and storage information */
        public array $shelfLifeStorage = [],
        /** @var array<FHIRReference> manufacturer Manufacturer of this packaging item (multiple means these are all potential manufacturers) */
        public array $manufacturer = [],
        /** @var array<FHIRPackagedProductDefinitionPackagingProperty> property General characteristics of this item */
        public array $property = [],
        /** @var array<FHIRPackagedProductDefinitionPackagingContainedItem> containedItem The item(s) within the packaging */
        public array $containedItem = [],
        /** @var array<FHIRPackagedProductDefinitionPackaging> packaging Allows containers (and parts of containers) within containers, still as a part of single packaged product */
        public array $packaging = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
