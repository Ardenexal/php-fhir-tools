<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/PackagedProductDefinition
 * @description A medically related item or items, in a container or package.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'PackagedProductDefinition',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/PackagedProductDefinition',
	fhirVersion: 'R4B',
)]
class FHIRPackagedProductDefinition extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier A unique identifier for this package as whole */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name A name for this package. Typically as listed in a drug formulary, catalogue, inventory etc */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type A high level category e.g. medicinal product, raw material, shipping container etc */
		public ?FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> packageFor The product that this is a pack for */
		public array $packageFor = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept status The status within the lifecycle of this item. High level - not intended to duplicate details elsewhere e.g. legal status, or authorization/marketing status */
		public ?FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime statusDate The date at which the given status became applicable */
		public ?FHIRDateTime $statusDate = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity> containedItemQuantity A total of the complete count of contained items of a particular type/form, independent of sub-packaging or organization. This can be considered as the pack size */
		public array $containedItemQuantity = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description Textual description. Note that this is not the name of the package or product */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPackagedProductDefinitionLegalStatusOfSupply> legalStatusOfSupply The legal status of supply of the packaged item as classified by the regulator */
		public array $legalStatusOfSupply = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarketingStatus> marketingStatus Allows specifying that an item is on the market for sale, or that it is not available, and the dates and locations associated */
		public array $marketingStatus = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> characteristic Allows the key features to be recorded, such as "hospital pack", "nurse prescribable" */
		public array $characteristic = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean copackagedIndicator If the drug product is supplied with another item such as a diluent or adjuvant */
		public ?FHIRBoolean $copackagedIndicator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> manufacturer Manufacturer of this package type (multiple means these are all possible manufacturers) */
		public array $manufacturer = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPackagedProductDefinitionPackage package A packaging item, as a container for medically related items, possibly with other packaging items within, or a packaging component, such as bottle cap */
		public ?FHIRPackagedProductDefinitionPackage $package = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
