<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @description Unique device identifier (UDI) assigned to device label or package.  Note that the Device may include multiple udiCarriers as it either may include just the udiCarrier for the jurisdiction it is sold, or for multiple jurisdictions it could have been sold.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Device', elementPath: 'Device.udiCarrier', fhirVersion: 'R4')]
class FHIRDeviceUdiCarrier extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null deviceIdentifier Mandatory fixed portion of UDI */
        public FHIRString|string|null $deviceIdentifier = null,
        /** @var FHIRUri|null issuer UDI Issuing Organization */
        public ?FHIRUri $issuer = null,
        /** @var FHIRUri|null jurisdiction Regional UDI authority */
        public ?FHIRUri $jurisdiction = null,
        /** @var FHIRBase64Binary|null carrierAIDC UDI Machine Readable Barcode String */
        public ?FHIRBase64Binary $carrierAIDC = null,
        /** @var FHIRString|string|null carrierHRF UDI Human Readable Barcode String */
        public FHIRString|string|null $carrierHRF = null,
        /** @var FHIRUDIEntryTypeType|null entryType barcode | rfid | manual + */
        public ?FHIRUDIEntryTypeType $entryType = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
