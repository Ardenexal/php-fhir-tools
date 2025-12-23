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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier> identifier Instance identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference definition The reference to the definition for the device */
		public ?FHIRReference $definition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceUdiCarrier> udiCarrier Unique Device Identifier (UDI) Barcode string */
		public array $udiCarrier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRFHIRDeviceStatusType status active | inactive | entered-in-error | unknown */
		public ?FHIRFHIRDeviceStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> statusReason online | paused | standby | offline | not-ready | transduc-discon | hw-discon | off */
		public array $statusReason = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string distinctIdentifier The distinct identification string */
		public FHIRString|string|null $distinctIdentifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string manufacturer Name of device manufacturer */
		public FHIRString|string|null $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime manufactureDate Date when the device was made */
		public ?FHIRDateTime $manufactureDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime expirationDate Date and time of expiry of this device (if applicable) */
		public ?FHIRDateTime $expirationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string lotNumber Lot number of manufacture */
		public FHIRString|string|null $lotNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string serialNumber Serial number assigned by the manufacturer */
		public FHIRString|string|null $serialNumber = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceDeviceName> deviceName The name of the device as given by the manufacturer */
		public array $deviceName = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string modelNumber The model number for the device */
		public FHIRString|string|null $modelNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string partNumber The part number of the device */
		public FHIRString|string|null $partNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type The kind or type of device */
		public ?FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
		public array $specialization = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceVersion> version The actual design of the device or software version running on the device */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDeviceProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
		public array $property = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference patient Patient to whom Device is affixed */
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference owner Organization responsible for device */
		public ?FHIRReference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactPoint> contact Details for human/organization for support */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference location Where the device is found */
		public ?FHIRReference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri url Network address to contact device */
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAnnotation> note Device notes and comments */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> safety Safety Characteristics of Device */
		public array $safety = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference parent The parent device */
		public ?FHIRReference $parent = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
