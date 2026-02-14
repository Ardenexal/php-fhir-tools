<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Device;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UDIEntryTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @description Unique device identifier (UDI) assigned to device label or package.  Note that the Device may include multiple udiCarriers as it either may include just the udiCarrier for the jurisdiction it is sold, or for multiple jurisdictions it could have been sold.
 */
#[FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.udiCarrier', fhirVersion: 'R4')]
class DeviceUdiCarrier extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null deviceIdentifier Mandatory fixed portion of UDI */
        public StringPrimitive|string|null $deviceIdentifier = null,
        /** @var UriPrimitive|null issuer UDI Issuing Organization */
        public ?UriPrimitive $issuer = null,
        /** @var UriPrimitive|null jurisdiction Regional UDI authority */
        public ?UriPrimitive $jurisdiction = null,
        /** @var Base64BinaryPrimitive|null carrierAIDC UDI Machine Readable Barcode String */
        public ?Base64BinaryPrimitive $carrierAIDC = null,
        /** @var StringPrimitive|string|null carrierHRF UDI Human Readable Barcode String */
        public StringPrimitive|string|null $carrierHRF = null,
        /** @var UDIEntryTypeType|null entryType barcode | rfid | manual + */
        public ?UDIEntryTypeType $entryType = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
