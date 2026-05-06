<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Count;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDeviceStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceConformsTo;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceName;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceUdiCarrier;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceVersion;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Device
 *
 * @description A type of a manufactured item that is used in the provision of healthcare without being substantially changed through that activity. The device may be a medical or non-medical device.
 */
#[FhirResource(type: 'Device', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Device', fhirVersion: 'R5')]
class DeviceResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Instance identifier */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var StringPrimitive|string|null displayName The name used to display by default when the device is referenced */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $displayName = null,
        /** @var CodeableReference|null definition The reference to the definition for the device */
        #[FhirProperty(fhirType: 'CodeableReference', propertyKind: 'complex')]
        public ?CodeableReference $definition = null,
        /** @var array<DeviceUdiCarrier> udiCarrier Unique Device Identifier (UDI) Barcode string */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceUdiCarrier',
        )]
        public array $udiCarrier = [],
        /** @var FHIRDeviceStatusType|null status active | inactive | entered-in-error */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?FHIRDeviceStatusType $status = null,
        /** @var CodeableConcept|null availabilityStatus lost | damaged | destroyed | available */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $availabilityStatus = null,
        /** @var Identifier|null biologicalSourceEvent An identifier that supports traceability to the event during which material in this product from one or more biological entities was obtained or pooled */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $biologicalSourceEvent = null,
        /** @var StringPrimitive|string|null manufacturer Name of device manufacturer */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $manufacturer = null,
        /** @var DateTimePrimitive|null manufactureDate Date when the device was made */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $manufactureDate = null,
        /** @var DateTimePrimitive|null expirationDate Date and time of expiry of this device (if applicable) */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $expirationDate = null,
        /** @var StringPrimitive|string|null lotNumber Lot number of manufacture */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $lotNumber = null,
        /** @var StringPrimitive|string|null serialNumber Serial number assigned by the manufacturer */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $serialNumber = null,
        /** @var array<DeviceName> name The name or names of the device as known to the manufacturer and/or patient */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceName',
        )]
        public array $name = [],
        /** @var StringPrimitive|string|null modelNumber The manufacturer's model number for the device */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $modelNumber = null,
        /** @var StringPrimitive|string|null partNumber The part number or catalog number of the device */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $partNumber = null,
        /** @var array<CodeableConcept> category Indicates a high-level grouping of the device */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $category = [],
        /** @var array<CodeableConcept> type The kind or type of device */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $type = [],
        /** @var array<DeviceVersion> version The actual design of the device or software version running on the device */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceVersion',
        )]
        public array $version = [],
        /** @var array<DeviceConformsTo> conformsTo Identifies the standards, specifications, or formal guidances for the capabilities supported by the device */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceConformsTo',
        )]
        public array $conformsTo = [],
        /** @var array<DeviceProperty> property Inherent, essentially fixed, characteristics of the device.  e.g., time properties, size, material, etc. */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Device\DeviceProperty',
        )]
        public array $property = [],
        /** @var CodeableConcept|null mode The designated condition for performing a task */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $mode = null,
        /** @var Count|null cycle The series of occurrences that repeats during the operation of the device */
        #[FhirProperty(fhirType: 'Count', propertyKind: 'complex')]
        public ?Count $cycle = null,
        /** @var Duration|null duration A measurement of time during the device's operation (e.g., days, hours, mins, etc.) */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $duration = null,
        /** @var Reference|null owner Organization responsible for device */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $owner = null,
        /** @var array<ContactPoint> contact Details for human/organization for support */
        #[FhirProperty(
            fhirType: 'ContactPoint',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactPoint',
        )]
        public array $contact = [],
        /** @var Reference|null location Where the device is found */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $location = null,
        /** @var UriPrimitive|null url Network address to contact device */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $url = null,
        /** @var array<Reference> endpoint Technical endpoints providing access to electronic services provided by the device */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $endpoint = [],
        /** @var array<CodeableReference> gateway Linked device acting as a communication/data collector, translator or controller */
        #[FhirProperty(
            fhirType: 'CodeableReference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
        )]
        public array $gateway = [],
        /** @var array<Annotation> note Device notes and comments */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
        )]
        public array $note = [],
        /** @var array<CodeableConcept> safety Safety Characteristics of Device */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $safety = [],
        /** @var Reference|null parent The higher level or encompassing device that this device is a logical part of */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $parent = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
