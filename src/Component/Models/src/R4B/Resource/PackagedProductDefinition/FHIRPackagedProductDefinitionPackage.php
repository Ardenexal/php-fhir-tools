<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;

/**
 * @description A packaging item, as a container for medically related items, possibly with other packaging items within, or a packaging component, such as bottle cap (which is not a device or a medication manufactured item).
 */
#[FHIRBackboneElement(parentResource: 'PackagedProductDefinition', elementPath: 'PackagedProductDefinition.package', fhirVersion: 'R4B')]
class FHIRPackagedProductDefinitionPackage extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRInteger|null quantity The quantity of this level of packaging in the package that contains it (with the outermost level being 1) */
        public ?FHIRInteger $quantity = null,
        /** @var array<FHIRCodeableConcept> material Material type of the package item */
        public array $material = [],
        /** @var array<FHIRCodeableConcept> alternateMaterial A possible alternate material for this part of the packaging, that is allowed to be used instead of the usual material */
        public array $alternateMaterial = [],
        /** @var array<FHIRPackagedProductDefinitionPackageShelfLifeStorage> shelfLifeStorage Shelf Life and storage information */
        public array $shelfLifeStorage = [],
        /** @var array<FHIRReference> manufacturer Manufacturer of this package Item (multiple means these are all possible manufacturers) */
        public array $manufacturer = [],
        /** @var array<FHIRPackagedProductDefinitionPackageProperty> property General characteristics of this item */
        public array $property = [],
        /** @var array<FHIRPackagedProductDefinitionPackageContainedItem> containedItem The item(s) within the packaging */
        public array $containedItem = [],
        /** @var array<FHIRPackagedProductDefinitionPackage> package Allows containers (and parts of containers) within containers, still a single packaged product */
        public array $package = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
