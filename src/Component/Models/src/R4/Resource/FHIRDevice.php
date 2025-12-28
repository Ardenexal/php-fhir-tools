<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Device
 *
 * @description A type of a manufactured item that is used in the provision of healthcare without being substantially changed through that activity. The device may be a medical or non-medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Device', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Device', fhirVersion: 'R4')]
class FHIRDevice extends FHIRDomainResource
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
        /** @var FHIRReference|null definition The reference to the definition for the device */
        public ?\FHIRReference $definition = null,
        /** @var array<FHIRDeviceUdiCarrier> udiCarrier Unique Device Identifier (UDI) Barcode string */
        public array $udiCarrier = [],
        /** @var FHIRFHIRDeviceStatusType|null status active | inactive | entered-in-error | unknown */
        public ?\FHIRFHIRDeviceStatusType $status = null,
        /** @var array<FHIRCodeableConcept> statusReason online | paused | standby | offline | not-ready | transduc-discon | hw-discon | off */
        public array $statusReason = [],
        /** @var FHIRString|string|null distinctIdentifier The distinct identification string */
        public \FHIRString|string|null $distinctIdentifier = null,
        /** @var FHIRString|string|null manufacturer Name of device manufacturer */
        public \FHIRString|string|null $manufacturer = null,
        /** @var FHIRDateTime|null manufactureDate Date when the device was made */
        public ?\FHIRDateTime $manufactureDate = null,
        /** @var FHIRDateTime|null expirationDate Date and time of expiry of this device (if applicable) */
        public ?\FHIRDateTime $expirationDate = null,
        /** @var FHIRString|string|null lotNumber Lot number of manufacture */
        public \FHIRString|string|null $lotNumber = null,
        /** @var FHIRString|string|null serialNumber Serial number assigned by the manufacturer */
        public \FHIRString|string|null $serialNumber = null,
        /** @var array<FHIRDeviceDeviceName> deviceName The name of the device as given by the manufacturer */
        public array $deviceName = [],
        /** @var FHIRString|string|null modelNumber The model number for the device */
        public \FHIRString|string|null $modelNumber = null,
        /** @var FHIRString|string|null partNumber The part number of the device */
        public \FHIRString|string|null $partNumber = null,
        /** @var FHIRCodeableConcept|null type The kind or type of device */
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRDeviceSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
        public array $specialization = [],
        /** @var array<FHIRDeviceVersion> version The actual design of the device or software version running on the device */
        public array $version = [],
        /** @var array<FHIRDeviceProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
        public array $property = [],
        /** @var FHIRReference|null patient Patient to whom Device is affixed */
        public ?\FHIRReference $patient = null,
        /** @var FHIRReference|null owner Organization responsible for device */
        public ?\FHIRReference $owner = null,
        /** @var array<FHIRContactPoint> contact Details for human/organization for support */
        public array $contact = [],
        /** @var FHIRReference|null location Where the device is found */
        public ?\FHIRReference $location = null,
        /** @var FHIRUri|null url Network address to contact device */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRAnnotation> note Device notes and comments */
        public array $note = [],
        /** @var array<FHIRCodeableConcept> safety Safety Characteristics of Device */
        public array $safety = [],
        /** @var FHIRReference|null parent The parent device */
        public ?\FHIRReference $parent = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
