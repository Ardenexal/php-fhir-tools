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
class FHIRDeviceDefinition extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Instance identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string */
		public array $udiDeviceIdentifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference manufacturerX Name of device manufacturer */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference|null $manufacturerX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceDefinitionDeviceName> deviceName A name given to the device to identify it */
		public array $deviceName = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string modelNumber The model number for the device */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $modelNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type What kind of device or device system this is */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceDefinitionSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
		public array $specialization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string> version Available versions */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> safety Safety characteristics of the device */
		public array $safety = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRProductShelfLife> shelfLifeStorage Shelf Life and storage information */
		public array $shelfLifeStorage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRProdCharacteristic physicalCharacteristics Dimensions, color etc. */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRProdCharacteristic $physicalCharacteristics = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> languageCode Language code for the human-readable text strings produced by the device (all supported) */
		public array $languageCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceDefinitionCapability> capability Device capabilities */
		public array $capability = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceDefinitionProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
		public array $property = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference owner Organization responsible for device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactPoint> contact Details for human/organization for support */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri url Network address to contact device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri onlineInformation Access to on-line information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $onlineInformation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Device notes and comments */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity quantity The quantity of the device present in the packaging (e.g. the number of devices present in a pack, or the number of devices in the same package of the medicinal product) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $quantity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference parentDevice The parent device it can be part of */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $parentDevice = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceDefinitionMaterial> material A substance used to create the material(s) of which the device is made */
		public array $material = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
