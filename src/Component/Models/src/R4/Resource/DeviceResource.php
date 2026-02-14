<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDeviceStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceDeviceName;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceProperty;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceSpecialization;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceUdiCarrier;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Device\DeviceVersion;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Device
 *
 * @description A type of a manufactured item that is used in the provision of healthcare without being substantially changed through that activity. The device may be a medical or non-medical device.
 */
#[FhirResource(type: 'Device', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Device', fhirVersion: 'R4')]
class DeviceResource extends DomainResourceResource
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
        /** @var Reference|null definition The reference to the definition for the device */
        public ?Reference $definition = null,
        /** @var array<DeviceUdiCarrier> udiCarrier Unique Device Identifier (UDI) Barcode string */
        public array $udiCarrier = [],
        /** @var FHIRDeviceStatusType|null status active | inactive | entered-in-error | unknown */
        public ?FHIRDeviceStatusType $status = null,
        /** @var array<CodeableConcept> statusReason online | paused | standby | offline | not-ready | transduc-discon | hw-discon | off */
        public array $statusReason = [],
        /** @var StringPrimitive|string|null distinctIdentifier The distinct identification string */
        public StringPrimitive|string|null $distinctIdentifier = null,
        /** @var StringPrimitive|string|null manufacturer Name of device manufacturer */
        public StringPrimitive|string|null $manufacturer = null,
        /** @var DateTimePrimitive|null manufactureDate Date when the device was made */
        public ?DateTimePrimitive $manufactureDate = null,
        /** @var DateTimePrimitive|null expirationDate Date and time of expiry of this device (if applicable) */
        public ?DateTimePrimitive $expirationDate = null,
        /** @var StringPrimitive|string|null lotNumber Lot number of manufacture */
        public StringPrimitive|string|null $lotNumber = null,
        /** @var StringPrimitive|string|null serialNumber Serial number assigned by the manufacturer */
        public StringPrimitive|string|null $serialNumber = null,
        /** @var array<DeviceDeviceName> deviceName The name of the device as given by the manufacturer */
        public array $deviceName = [],
        /** @var StringPrimitive|string|null modelNumber The model number for the device */
        public StringPrimitive|string|null $modelNumber = null,
        /** @var StringPrimitive|string|null partNumber The part number of the device */
        public StringPrimitive|string|null $partNumber = null,
        /** @var CodeableConcept|null type The kind or type of device */
        public ?CodeableConcept $type = null,
        /** @var array<DeviceSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
        public array $specialization = [],
        /** @var array<DeviceVersion> version The actual design of the device or software version running on the device */
        public array $version = [],
        /** @var array<DeviceProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
        public array $property = [],
        /** @var Reference|null patient Patient to whom Device is affixed */
        public ?Reference $patient = null,
        /** @var Reference|null owner Organization responsible for device */
        public ?Reference $owner = null,
        /** @var array<ContactPoint> contact Details for human/organization for support */
        public array $contact = [],
        /** @var Reference|null location Where the device is found */
        public ?Reference $location = null,
        /** @var UriPrimitive|null url Network address to contact device */
        public ?UriPrimitive $url = null,
        /** @var array<Annotation> note Device notes and comments */
        public array $note = [],
        /** @var array<CodeableConcept> safety Safety Characteristics of Device */
        public array $safety = [],
        /** @var Reference|null parent The parent device */
        public ?Reference $parent = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
