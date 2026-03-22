<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ProdCharacteristic;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ProductShelfLife;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionCapability;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionDeviceName;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionMaterial;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionSpecialization;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionUdiDeviceIdentifier;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceDefinition
 *
 * @description The characteristics, operational status and capabilities of a medical-related component of a medical device.
 */
#[FhirResource(
    type: 'DeviceDefinition',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceDefinition',
    fhirVersion: 'R4B',
)]
class DeviceDefinitionResource extends DomainResourceResource
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
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
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
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var array<DeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionUdiDeviceIdentifier',
        )]
        public array $udiDeviceIdentifier = [],
        /** @var StringPrimitive|string|Reference|null manufacturer Name of device manufacturer */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive',
                    'jsonKey'      => 'manufacturerString',
                ],
                [
                    'fhirType'     => 'Reference',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
                    'jsonKey'      => 'manufacturerReference',
                ],
            ],
        )]
        public StringPrimitive|string|Reference|null $manufacturer = null,
        /** @var array<DeviceDefinitionDeviceName> deviceName A name given to the device to identify it */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionDeviceName',
        )]
        public array $deviceName = [],
        /** @var StringPrimitive|string|null modelNumber The model number for the device */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $modelNumber = null,
        /** @var CodeableConcept|null type What kind of device or device system this is */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var array<DeviceDefinitionSpecialization> specialization The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionSpecialization',
        )]
        public array $specialization = [],
        /** @var array<StringPrimitive|string> version Available versions */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $version = [],
        /** @var array<CodeableConcept> safety Safety characteristics of the device */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $safety = [],
        /** @var array<ProductShelfLife> shelfLifeStorage Shelf Life and storage information */
        #[FhirProperty(
            fhirType: 'ProductShelfLife',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ProductShelfLife',
        )]
        public array $shelfLifeStorage = [],
        /** @var ProdCharacteristic|null physicalCharacteristics Dimensions, color etc. */
        #[FhirProperty(fhirType: 'ProdCharacteristic', propertyKind: 'complex')]
        public ?ProdCharacteristic $physicalCharacteristics = null,
        /** @var array<CodeableConcept> languageCode Language code for the human-readable text strings produced by the device (all supported) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        public array $languageCode = [],
        /** @var array<DeviceDefinitionCapability> capability Device capabilities */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionCapability',
        )]
        public array $capability = [],
        /** @var array<DeviceDefinitionProperty> property The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionProperty',
        )]
        public array $property = [],
        /** @var Reference|null owner Organization responsible for device */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $owner = null,
        /** @var array<ContactPoint> contact Details for human/organization for support */
        #[FhirProperty(
            fhirType: 'ContactPoint',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactPoint',
        )]
        public array $contact = [],
        /** @var UriPrimitive|null url Network address to contact device */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $url = null,
        /** @var UriPrimitive|null onlineInformation Access to on-line information */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $onlineInformation = null,
        /** @var array<Annotation> note Device notes and comments */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Annotation',
        )]
        public array $note = [],
        /** @var Quantity|null quantity The quantity of the device present in the packaging (e.g. the number of devices present in a pack, or the number of devices in the same package of the medicinal product) */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var Reference|null parentDevice The parent device it can be part of */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $parentDevice = null,
        /** @var array<DeviceDefinitionMaterial> material A substance used to create the material(s) of which the device is made */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\DeviceDefinition\DeviceDefinitionMaterial',
        )]
        public array $material = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
