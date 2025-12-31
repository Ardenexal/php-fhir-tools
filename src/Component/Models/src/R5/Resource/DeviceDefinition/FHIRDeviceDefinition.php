<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Orders and Observations)
 * @see http://hl7.org/fhir/StructureDefinition/DeviceDefinition
 * @description The characteristics, operational status and capabilities of a medical-related component of a medical device.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'DeviceDefinition',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/DeviceDefinition',
	fhirVersion: 'R5',
)]
class FHIRDeviceDefinition extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Additional information to describe the device */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Instance identifier */
		public array $identifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionUdiDeviceIdentifier> udiDeviceIdentifier Unique Device Identifier (UDI) Barcode string */
		public array $udiDeviceIdentifier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionRegulatoryIdentifier> regulatoryIdentifier Regulatory identifier(s) associated with this device */
		public array $regulatoryIdentifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string partNumber The part number or catalog number of the device */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $partNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference manufacturer Name of device manufacturer */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $manufacturer = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionDeviceName> deviceName The name or names of the device as given by the manufacturer */
		public array $deviceName = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string modelNumber The catalog or model number for the device for example as defined by the manufacturer */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $modelNumber = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionClassification> classification What kind of device or device system this is */
		public array $classification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionConformsTo> conformsTo Identifies the standards, specifications, or formal guidances for the capabilities supported by the device */
		public array $conformsTo = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionHasPart> hasPart A device, part of the current one */
		public array $hasPart = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionPackaging> packaging Information about the packaging of the device, i.e. how the device is packaged */
		public array $packaging = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionVersion> version The version of the device or software */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> safety Safety characteristics of the device */
		public array $safety = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRProductShelfLife> shelfLifeStorage Shelf Life and storage information */
		public array $shelfLifeStorage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> languageCode Language code for the human-readable text strings produced by the device (all supported) */
		public array $languageCode = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionProperty> property Inherent, essentially fixed, characteristics of this kind of device, e.g., time properties, size, etc */
		public array $property = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference owner Organization responsible for device */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $owner = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint> contact Details for human/organization for support */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionLink> link An associated device, attached to, used with, communicating with or linking a previous or new device model to the focal device */
		public array $link = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Device notes and comments */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionMaterial> material A substance used to create the material(s) of which the device is made */
		public array $material = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDeviceProductionIdentifierInUDIType> productionIdentifierInUDI lot-number | manufactured-date | serial-number | expiration-date | biological-source | software-version */
		public array $productionIdentifierInUDI = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionGuideline guideline Information aimed at providing directions for the usage of this model of device */
		public ?FHIRDeviceDefinitionGuideline $guideline = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionCorrectiveAction correctiveAction Tracking of latest field safety corrective action */
		public ?FHIRDeviceDefinitionCorrectiveAction $correctiveAction = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDeviceDefinitionChargeItem> chargeItem Billing code or reference associated with the device */
		public array $chargeItem = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
