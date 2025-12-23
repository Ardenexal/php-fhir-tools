<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/Device
 * @description A type of a manufactured item that is used in the provision of healthcare without being substantially changed through that activity. The device may be a medical or non-medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Device', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Device', fhirVersion: 'R5')]
class FHIRDevice extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Instance identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string displayName The name used to display by default when the device is referenced */
		public FHIRString|string|null $displayName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference definition The reference to the definition for the device */
		public ?FHIRCodeableReference $definition = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceUdiCarrier> udiCarrier Unique Device Identifier (UDI) Barcode string */
		public array $udiCarrier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRFHIRDeviceStatusType status active | inactive | entered-in-error */
		public ?FHIRFHIRDeviceStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept availabilityStatus lost | damaged | destroyed | available */
		public ?FHIRCodeableConcept $availabilityStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier biologicalSourceEvent An identifier that supports traceability to the event during which material in this product from one or more biological entities was obtained or pooled */
		public ?FHIRIdentifier $biologicalSourceEvent = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string manufacturer Name of device manufacturer */
		public FHIRString|string|null $manufacturer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime manufactureDate Date when the device was made */
		public ?FHIRDateTime $manufactureDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime expirationDate Date and time of expiry of this device (if applicable) */
		public ?FHIRDateTime $expirationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string lotNumber Lot number of manufacture */
		public FHIRString|string|null $lotNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string serialNumber Serial number assigned by the manufacturer */
		public FHIRString|string|null $serialNumber = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceName> name The name or names of the device as known to the manufacturer and/or patient */
		public array $name = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string modelNumber The manufacturer's model number for the device */
		public FHIRString|string|null $modelNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string partNumber The part number or catalog number of the device */
		public FHIRString|string|null $partNumber = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> category Indicates a high-level grouping of the device */
		public array $category = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> type The kind or type of device */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceVersion> version The actual design of the device or software version running on the device */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceConformsTo> conformsTo Identifies the standards, specifications, or formal guidances for the capabilities supported by the device */
		public array $conformsTo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceProperty> property Inherent, essentially fixed, characteristics of the device.  e.g., time properties, size, material, etc. */
		public array $property = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept mode The designated condition for performing a task */
		public ?FHIRCodeableConcept $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCount cycle The series of occurrences that repeats during the operation of the device */
		public ?FHIRCount $cycle = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDuration duration A measurement of time during the device's operation (e.g., days, hours, mins, etc.) */
		public ?FHIRDuration $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference owner Organization responsible for device */
		public ?FHIRReference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint> contact Details for human/organization for support */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference location Where the device is found */
		public ?FHIRReference $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri url Network address to contact device */
		public ?FHIRUri $url = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> endpoint Technical endpoints providing access to electronic services provided by the device */
		public array $endpoint = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> gateway Linked device acting as a communication/data collector, translator or controller */
		public array $gateway = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Device notes and comments */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> safety Safety Characteristics of Device */
		public array $safety = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference parent The higher level or encompassing device that this device is a logical part of */
		public ?FHIRReference $parent = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
