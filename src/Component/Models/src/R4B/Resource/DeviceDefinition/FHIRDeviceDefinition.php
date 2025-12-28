<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceDefinition
 *
 * @description The characteristics, operational status and capabilities of a medical-related component of a medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'DeviceDefinition',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceDefinition',
    fhirVersion: 'R4B',
)]
class FHIRDeviceDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Instance identifier */
        public array $identifier = [],
        /** @var array<FHIRDeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string */
        public array $udiDeviceIdentifier = [],
        /** @var FHIRString|string|FHIRReference|null manufacturerX Name of device manufacturer */
        public \FHIRString|string|\FHIRReference|null $manufacturerX = null,
        /** @var array<FHIRDeviceDefinitionDeviceName> deviceName A name given to the device to identify it */
        public array $deviceName = [],
        /** @var FHIRString|string|null modelNumber The model number for the device */
        public \FHIRString|string|null $modelNumber = null,
        /** @var FHIRCodeableConcept|null type What kind of device or device system this is */
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRDeviceDefinitionSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
        public array $specialization = [],
        /** @var array<FHIRString|string> version Available versions */
        public array $version = [],
        /** @var array<FHIRCodeableConcept> safety Safety characteristics of the device */
        public array $safety = [],
        /** @var array<FHIRProductShelfLife> shelfLifeStorage Shelf Life and storage information */
        public array $shelfLifeStorage = [],
        /** @var FHIRProdCharacteristic|null physicalCharacteristics Dimensions, color etc. */
        public ?\FHIRProdCharacteristic $physicalCharacteristics = null,
        /** @var array<FHIRCodeableConcept> languageCode Language code for the human-readable text strings produced by the device (all supported) */
        public array $languageCode = [],
        /** @var array<FHIRDeviceDefinitionCapability> capability Device capabilities */
        public array $capability = [],
        /** @var array<FHIRDeviceDefinitionProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
        public array $property = [],
        /** @var FHIRReference|null owner Organization responsible for device */
        public ?\FHIRReference $owner = null,
        /** @var array<FHIRContactPoint> contact Details for human/organization for support */
        public array $contact = [],
        /** @var FHIRUri|null url Network address to contact device */
        public ?\FHIRUri $url = null,
        /** @var FHIRUri|null onlineInformation Access to on-line information */
        public ?\FHIRUri $onlineInformation = null,
        /** @var array<FHIRAnnotation> note Device notes and comments */
        public array $note = [],
        /** @var FHIRQuantity|null quantity The quantity of the device present in the packaging (e.g. the number of devices present in a pack, or the number of devices in the same package of the medicinal product) */
        public ?\FHIRQuantity $quantity = null,
        /** @var FHIRReference|null parentDevice The parent device it can be part of */
        public ?\FHIRReference $parentDevice = null,
        /** @var array<FHIRDeviceDefinitionMaterial> material A substance used to create the material(s) of which the device is made */
        public array $material = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
