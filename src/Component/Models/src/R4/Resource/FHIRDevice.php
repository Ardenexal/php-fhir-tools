<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/Device
 * @description A type of a manufactured item that is used in the provision of healthcare without being substantially changed through that activity. The device may be a medical or non-medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Device', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Device', fhirVersion: 'R4')]
class FHIRDevice extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference definition The reference to the definition for the device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $definition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceUdiCarrier> udiCarrier Unique Device Identifier (UDI) Barcode string */
		public array $udiCarrier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFHIRDeviceStatusType status active | inactive | entered-in-error | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRFHIRDeviceStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> statusReason online | paused | standby | offline | not-ready | transduc-discon | hw-discon | off */
		public array $statusReason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string distinctIdentifier The distinct identification string */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $distinctIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string manufacturer Name of device manufacturer */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime manufactureDate Date when the device was made */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $manufactureDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime expirationDate Date and time of expiry of this device (if applicable) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime $expirationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string lotNumber Lot number of manufacture */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $lotNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string serialNumber Serial number assigned by the manufacturer */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $serialNumber = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceDeviceName> deviceName The name of the device as given by the manufacturer */
		public array $deviceName = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string modelNumber The model number for the device */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $modelNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string partNumber The part number of the device */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $partNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept type The kind or type of device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
		public array $specialization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceVersion> version The actual design of the device or software version running on the device */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
		public array $property = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference patient Patient to whom Device is affixed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference owner Organization responsible for device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactPoint> contact Details for human/organization for support */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference location Where the device is found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri url Network address to contact device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation> note Device notes and comments */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> safety Safety Characteristics of Device */
		public array $safety = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference parent The parent device */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $parent = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
