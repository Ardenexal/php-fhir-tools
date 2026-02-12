<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/DeviceDefinition
 * @description The characteristics, operational status and capabilities of a medical-related component of a medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'DeviceDefinition',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/DeviceDefinition',
	fhirVersion: 'R4',
)]
class DeviceDefinitionResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Instance identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string */
		public array $udiDeviceIdentifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference manufacturerX Name of device manufacturer */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $manufacturerX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionDeviceName> deviceName A name given to the device to identify it */
		public array $deviceName = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string modelNumber The model number for the device */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $modelNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type What kind of device or device system this is */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
		public array $specialization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> version Available versions */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> safety Safety characteristics of the device */
		public array $safety = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ProductShelfLife> shelfLifeStorage Shelf Life and storage information */
		public array $shelfLifeStorage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ProdCharacteristic physicalCharacteristics Dimensions, color etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ProdCharacteristic $physicalCharacteristics = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> languageCode Language code for the human-readable text strings produced by the device (all supported) */
		public array $languageCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionCapability> capability Device capabilities */
		public array $capability = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
		public array $property = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference owner Organization responsible for device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> contact Details for human/organization for support */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive url Network address to contact device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive onlineInformation Access to on-line information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $onlineInformation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Device notes and comments */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity quantity The quantity of the device present in the packaging (e.g. the number of devices present in a pack, or the number of devices in the same package of the medicinal product) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference parentDevice The parent device it can be part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $parentDevice = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition\DeviceDefinitionMaterial> material A substance used to create the material(s) of which the device is made */
		public array $material = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
