<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ProdCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ProductShelfLife;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionCapability;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionDeviceName;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionMaterial;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionProperty;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionSpecialization;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionUdiDeviceIdentifier;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceDefinition
 *
 * @description The characteristics, operational status and capabilities of a medical-related component of a medical device.
 */
#[FhirResource(
    type: 'DeviceDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceDefinition',
    fhirVersion: 'R4',
)]
class DeviceDefinitionResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Instance identifier */
        public array $identifier = [],
        /** @var array<DeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string */
        public array $udiDeviceIdentifier = [],
        /** @var StringPrimitive|string|Reference|null manufacturerX Name of device manufacturer */
        public StringPrimitive|string|Reference|null $manufacturerX = null,
        /** @var array<DeviceDefinitionDeviceName> deviceName A name given to the device to identify it */
        public array $deviceName = [],
        /** @var StringPrimitive|string|null modelNumber The model number for the device */
        public StringPrimitive|string|null $modelNumber = null,
        /** @var CodeableConcept|null type What kind of device or device system this is */
        public ?CodeableConcept $type = null,
        /** @var array<DeviceDefinitionSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
        public array $specialization = [],
        /** @var array<StringPrimitive|string> version Available versions */
        public array $version = [],
        /** @var array<CodeableConcept> safety Safety characteristics of the device */
        public array $safety = [],
        /** @var array<ProductShelfLife> shelfLifeStorage Shelf Life and storage information */
        public array $shelfLifeStorage = [],
        /** @var ProdCharacteristic|null physicalCharacteristics Dimensions, color etc. */
        public ?ProdCharacteristic $physicalCharacteristics = null,
        /** @var array<CodeableConcept> languageCode Language code for the human-readable text strings produced by the device (all supported) */
        public array $languageCode = [],
        /** @var array<DeviceDefinitionCapability> capability Device capabilities */
        public array $capability = [],
        /** @var array<DeviceDefinitionProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
        public array $property = [],
        /** @var Reference|null owner Organization responsible for device */
        public ?Reference $owner = null,
        /** @var array<ContactPoint> contact Details for human/organization for support */
        public array $contact = [],
        /** @var UriPrimitive|null url Network address to contact device */
        public ?UriPrimitive $url = null,
        /** @var UriPrimitive|null onlineInformation Access to on-line information */
        public ?UriPrimitive $onlineInformation = null,
        /** @var array<Annotation> note Device notes and comments */
        public array $note = [],
        /** @var Quantity|null quantity The quantity of the device present in the packaging (e.g. the number of devices present in a pack, or the number of devices in the same package of the medicinal product) */
        public ?Quantity $quantity = null,
        /** @var Reference|null parentDevice The parent device it can be part of */
        public ?Reference $parentDevice = null,
        /** @var array<DeviceDefinitionMaterial> material A substance used to create the material(s) of which the device is made */
        public array $material = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
