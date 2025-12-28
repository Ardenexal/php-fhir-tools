<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Information about the packaging of the device, i.e. how the device is packaged.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.packaging', fhirVersion: 'R5')]
class FHIRDeviceDefinitionPackaging extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Business identifier of the packaged medication */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRCodeableConcept|null type A code that defines the specific type of packaging */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRInteger|null count The number of items contained in the package (devices or sub-packages) */
        public ?\FHIRInteger $count = null,
        /** @var array<FHIRDeviceDefinitionPackagingDistributor> distributor An organization that distributes the packaged device */
        public array $distributor = [],
        /** @var array<FHIRDeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string on the packaging */
        public array $udiDeviceIdentifier = [],
        /** @var array<FHIRDeviceDefinitionPackaging> packaging Allows packages within packages */
        public array $packaging = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
