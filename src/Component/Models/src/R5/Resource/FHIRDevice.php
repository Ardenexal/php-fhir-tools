<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCount;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Device
 *
 * @description A type of a manufactured item that is used in the provision of healthcare without being substantially changed through that activity. The device may be a medical or non-medical device.
 */
#[FhirResource(type: 'Device', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Device', fhirVersion: 'R5')]
class FHIRDevice extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Instance identifier */
        public array $identifier = [],
        /** @var FHIRString|string|null displayName The name used to display by default when the device is referenced */
        public FHIRString|string|null $displayName = null,
        /** @var FHIRCodeableReference|null definition The reference to the definition for the device */
        public ?FHIRCodeableReference $definition = null,
        /** @var array<FHIRDeviceUdiCarrier> udiCarrier Unique Device Identifier (UDI) Barcode string */
        public array $udiCarrier = [],
        /** @var FHIRFHIRDeviceStatusType|null status active | inactive | entered-in-error */
        public ?FHIRFHIRDeviceStatusType $status = null,
        /** @var FHIRCodeableConcept|null availabilityStatus lost | damaged | destroyed | available */
        public ?FHIRCodeableConcept $availabilityStatus = null,
        /** @var FHIRIdentifier|null biologicalSourceEvent An identifier that supports traceability to the event during which material in this product from one or more biological entities was obtained or pooled */
        public ?FHIRIdentifier $biologicalSourceEvent = null,
        /** @var FHIRString|string|null manufacturer Name of device manufacturer */
        public FHIRString|string|null $manufacturer = null,
        /** @var FHIRDateTime|null manufactureDate Date when the device was made */
        public ?FHIRDateTime $manufactureDate = null,
        /** @var FHIRDateTime|null expirationDate Date and time of expiry of this device (if applicable) */
        public ?FHIRDateTime $expirationDate = null,
        /** @var FHIRString|string|null lotNumber Lot number of manufacture */
        public FHIRString|string|null $lotNumber = null,
        /** @var FHIRString|string|null serialNumber Serial number assigned by the manufacturer */
        public FHIRString|string|null $serialNumber = null,
        /** @var array<FHIRDeviceName> name The name or names of the device as known to the manufacturer and/or patient */
        public array $name = [],
        /** @var FHIRString|string|null modelNumber The manufacturer's model number for the device */
        public FHIRString|string|null $modelNumber = null,
        /** @var FHIRString|string|null partNumber The part number or catalog number of the device */
        public FHIRString|string|null $partNumber = null,
        /** @var array<FHIRCodeableConcept> category Indicates a high-level grouping of the device */
        public array $category = [],
        /** @var array<FHIRCodeableConcept> type The kind or type of device */
        public array $type = [],
        /** @var array<FHIRDeviceVersion> version The actual design of the device or software version running on the device */
        public array $version = [],
        /** @var array<FHIRDeviceConformsTo> conformsTo Identifies the standards, specifications, or formal guidances for the capabilities supported by the device */
        public array $conformsTo = [],
        /** @var array<FHIRDeviceProperty> property Inherent, essentially fixed, characteristics of the device.  e.g., time properties, size, material, etc. */
        public array $property = [],
        /** @var FHIRCodeableConcept|null mode The designated condition for performing a task */
        public ?FHIRCodeableConcept $mode = null,
        /** @var FHIRCount|null cycle The series of occurrences that repeats during the operation of the device */
        public ?FHIRCount $cycle = null,
        /** @var FHIRDuration|null duration A measurement of time during the device's operation (e.g., days, hours, mins, etc.) */
        public ?FHIRDuration $duration = null,
        /** @var FHIRReference|null owner Organization responsible for device */
        public ?FHIRReference $owner = null,
        /** @var array<FHIRContactPoint> contact Details for human/organization for support */
        public array $contact = [],
        /** @var FHIRReference|null location Where the device is found */
        public ?FHIRReference $location = null,
        /** @var FHIRUri|null url Network address to contact device */
        public ?FHIRUri $url = null,
        /** @var array<FHIRReference> endpoint Technical endpoints providing access to electronic services provided by the device */
        public array $endpoint = [],
        /** @var array<FHIRCodeableReference> gateway Linked device acting as a communication/data collector, translator or controller */
        public array $gateway = [],
        /** @var array<FHIRAnnotation> note Device notes and comments */
        public array $note = [],
        /** @var array<FHIRCodeableConcept> safety Safety Characteristics of Device */
        public array $safety = [],
        /** @var FHIRReference|null parent The higher level or encompassing device that this device is a logical part of */
        public ?FHIRReference $parent = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
