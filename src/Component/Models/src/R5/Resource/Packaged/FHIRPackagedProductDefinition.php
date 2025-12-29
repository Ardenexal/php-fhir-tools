<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/PackagedProductDefinition
 * @description A medically related item or items, in a container or package.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PackagedProductDefinition',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/PackagedProductDefinition',
	fhirVersion: 'R5',
)]
class FHIRPackagedProductDefinition extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier A unique identifier for this package as whole - not for the content of the package */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name A name for this package. Typically as listed in a drug formulary, catalogue, inventory etc */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type A high level category e.g. medicinal product, raw material, shipping container etc */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> packageFor The product that this is a pack for */
		public array $packageFor = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept status The status within the lifecycle of this item. High level - not intended to duplicate details elsewhere e.g. legal status, or authorization/marketing status */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime statusDate The date at which the given status became applicable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $statusDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity> containedItemQuantity A total of the complete count of contained items of a particular type/form, independent of sub-packaging or organization. This can be considered as the pack size. See also packaging.containedItem.amount (especially the long definition) */
		public array $containedItemQuantity = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Textual description. Note that this is not the name of the package or product */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPackagedProductDefinitionLegalStatusOfSupply> legalStatusOfSupply The legal status of supply of the packaged item as classified by the regulator */
		public array $legalStatusOfSupply = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMarketingStatus> marketingStatus Allows specifying that an item is on the market for sale, or that it is not available, and the dates and locations associated */
		public array $marketingStatus = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean copackagedIndicator Identifies if the drug product is supplied with another item such as a diluent or adjuvant */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $copackagedIndicator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> manufacturer Manufacturer of this package type (multiple means these are all possible manufacturers) */
		public array $manufacturer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> attachedDocument Additional information or supporting documentation about the packaged product */
		public array $attachedDocument = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPackagedProductDefinitionPackaging packaging A packaging item, as a container for medically related items, possibly with other packaging items within, or a packaging component, such as bottle cap */
		public ?FHIRPackagedProductDefinitionPackaging $packaging = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPackagedProductDefinitionPackagingProperty> characteristic Allows the key features to be recorded, such as "hospital pack", "nurse prescribable" */
		public array $characteristic = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
