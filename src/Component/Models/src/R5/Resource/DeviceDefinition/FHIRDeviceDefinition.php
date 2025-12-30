<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDeviceProductionIdentifierInUDIType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRProductShelfLife;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceDefinition
 *
 * @description The characteristics, operational status and capabilities of a medical-related component of a medical device.
 */
#[FhirResource(
    type: 'DeviceDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceDefinition',
    fhirVersion: 'R5',
)]
class FHIRDeviceDefinition extends FHIRDomainResource
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
        /** @var FHIRMarkdown|null description Additional information to describe the device */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRIdentifier> identifier Instance identifier */
        public array $identifier = [],
        /** @var array<FHIRDeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string */
        public array $udiDeviceIdentifier = [],
        /** @var array<FHIRDeviceDefinitionRegulatoryIdentifier> regulatoryIdentifier Regulatory identifier(s) associated with this device */
        public array $regulatoryIdentifier = [],
        /** @var FHIRString|string|null partNumber The part number or catalog number of the device */
        public FHIRString|string|null $partNumber = null,
        /** @var FHIRReference|null manufacturer Name of device manufacturer */
        public ?FHIRReference $manufacturer = null,
        /** @var array<FHIRDeviceDefinitionDeviceName> deviceName The name or names of the device as given by the manufacturer */
        public array $deviceName = [],
        /** @var FHIRString|string|null modelNumber The catalog or model number for the device for example as defined by the manufacturer */
        public FHIRString|string|null $modelNumber = null,
        /** @var array<FHIRDeviceDefinitionClassification> classification What kind of device or device system this is */
        public array $classification = [],
        /** @var array<FHIRDeviceDefinitionConformsTo> conformsTo Identifies the standards, specifications, or formal guidances for the capabilities supported by the device */
        public array $conformsTo = [],
        /** @var array<FHIRDeviceDefinitionHasPart> hasPart A device, part of the current one */
        public array $hasPart = [],
        /** @var array<FHIRDeviceDefinitionPackaging> packaging Information about the packaging of the device, i.e. how the device is packaged */
        public array $packaging = [],
        /** @var array<FHIRDeviceDefinitionVersion> version The version of the device or software */
        public array $version = [],
        /** @var array<FHIRCodeableConcept> safety Safety characteristics of the device */
        public array $safety = [],
        /** @var array<FHIRProductShelfLife> shelfLifeStorage Shelf Life and storage information */
        public array $shelfLifeStorage = [],
        /** @var array<FHIRCodeableConcept> languageCode Language code for the human-readable text strings produced by the device (all supported) */
        public array $languageCode = [],
        /** @var array<FHIRDeviceDefinitionProperty> property Inherent, essentially fixed, characteristics of this kind of device, e.g., time properties, size, etc */
        public array $property = [],
        /** @var FHIRReference|null owner Organization responsible for device */
        public ?FHIRReference $owner = null,
        /** @var array<FHIRContactPoint> contact Details for human/organization for support */
        public array $contact = [],
        /** @var array<FHIRDeviceDefinitionLink> link An associated device, attached to, used with, communicating with or linking a previous or new device model to the focal device */
        public array $link = [],
        /** @var array<FHIRAnnotation> note Device notes and comments */
        public array $note = [],
        /** @var array<FHIRDeviceDefinitionMaterial> material A substance used to create the material(s) of which the device is made */
        public array $material = [],
        /** @var array<FHIRDeviceProductionIdentifierInUDIType> productionIdentifierInUDI lot-number | manufactured-date | serial-number | expiration-date | biological-source | software-version */
        public array $productionIdentifierInUDI = [],
        /** @var FHIRDeviceDefinitionGuideline|null guideline Information aimed at providing directions for the usage of this model of device */
        public ?FHIRDeviceDefinitionGuideline $guideline = null,
        /** @var FHIRDeviceDefinitionCorrectiveAction|null correctiveAction Tracking of latest field safety corrective action */
        public ?FHIRDeviceDefinitionCorrectiveAction $correctiveAction = null,
        /** @var array<FHIRDeviceDefinitionChargeItem> chargeItem Billing code or reference associated with the device */
        public array $chargeItem = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
