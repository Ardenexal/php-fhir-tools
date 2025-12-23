<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/ManufacturedItemDefinition
 * @description The definition and characteristics of a medicinal manufactured item, such as a tablet or capsule, as contained in a packaged medicinal product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'ManufacturedItemDefinition',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/ManufacturedItemDefinition',
	fhirVersion: 'R5',
)]
class FHIRManufacturedItemDefinition extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Unique identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPublicationStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name A descriptive name applied to this item */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept manufacturedDoseForm Dose form as manufactured (before any necessary transformation) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRCodeableConcept $manufacturedDoseForm = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept unitOfPresentation The “real-world” units in which the quantity of the item is described */
		public ?FHIRCodeableConcept $unitOfPresentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> manufacturer Manufacturer of the item, one of several possible */
		public array $manufacturer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarketingStatus> marketingStatus Allows specifying that an item is on the market for sale, or that it is not available, and the dates and locations associated */
		public array $marketingStatus = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> ingredient The ingredients of this manufactured item. Only needed if these are not specified by incoming references from the Ingredient resource */
		public array $ingredient = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRManufacturedItemDefinitionProperty> property General characteristics of this item */
		public array $property = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRManufacturedItemDefinitionComponent> component Physical parts of the manufactured item, that it is intrisically made from. This is distinct from the ingredients that are part of its chemical makeup */
		public array $component = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
