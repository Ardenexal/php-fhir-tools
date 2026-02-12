<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/Device
 * @description A type of a manufactured item that is used in the provision of healthcare without being substantially changed through that activity. The device may be a medical or non-medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Device', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Device', fhirVersion: 'R4')]
class DeviceResource extends DomainResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference definition The reference to the definition for the device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $definition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceUdiCarrier> udiCarrier Unique Device Identifier (UDI) Barcode string */
		public array $udiCarrier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDeviceStatusType status active | inactive | entered-in-error | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDeviceStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> statusReason online | paused | standby | offline | not-ready | transduc-discon | hw-discon | off */
		public array $statusReason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string distinctIdentifier The distinct identification string */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $distinctIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string manufacturer Name of device manufacturer */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive manufactureDate Date when the device was made */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $manufactureDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive expirationDate Date and time of expiry of this device (if applicable) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $expirationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string lotNumber Lot number of manufacture */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $lotNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string serialNumber Serial number assigned by the manufacturer */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $serialNumber = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceDeviceName> deviceName The name of the device as given by the manufacturer */
		public array $deviceName = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string modelNumber The model number for the device */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $modelNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string partNumber The part number of the device */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $partNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type The kind or type of device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
		public array $specialization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceVersion> version The actual design of the device or software version running on the device */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
		public array $property = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference patient Patient to whom Device is affixed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference owner Organization responsible for device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> contact Details for human/organization for support */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference location Where the device is found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive url Network address to contact device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Device notes and comments */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> safety Safety Characteristics of Device */
		public array $safety = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference parent The parent device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $parent = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
