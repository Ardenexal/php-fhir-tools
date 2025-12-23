<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceDefinition
 * @description The detailed description of a substance, typically at a level beyond what is used for prescribing.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'SubstanceDefinition',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/SubstanceDefinition',
	fhirVersion: 'R4B',
)]
class FHIRSubstanceDefinition extends FHIRDomainResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier> identifier Identifier by which this substance is known */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string version A business level version identifier of the substance */
		public FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept status Status of substance within the catalogue e.g. active, retired */
		public ?FHIRCodeableConcept $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> classification A categorization, high level e.g. polymer or nucleic acid, or food, chemical, biological, or lower e.g. polymer linear or branch chain, or type of impurity */
		public array $classification = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept domain If the substance applies to human or veterinary use */
		public ?FHIRCodeableConcept $domain = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> grade The quality standard, established benchmark, to which substance complies (e.g. USP/NF, BP) */
		public array $grade = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description Textual description of the substance */
		public ?FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> informationSource Supporting literature */
		public array $informationSource = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Textual comment about the substance's catalogue or registry record */
		public array $note = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> manufacturer The entity that creates, makes, produces or fabricates the substance */
		public array $manufacturer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> supplier An entity that is the source for the substance. It may be different from the manufacturer */
		public array $supplier = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceDefinitionMoiety> moiety Moiety, for structural modifications */
		public array $moiety = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceDefinitionProperty> property General specifications for this substance */
		public array $property = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceDefinitionMolecularWeight> molecularWeight The molecular weight or weight range */
		public array $molecularWeight = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceDefinitionStructure structure Structural information */
		public ?FHIRSubstanceDefinitionStructure $structure = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceDefinitionCode> code Codes associated with the substance */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceDefinitionName> name Names applicable to this substance */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceDefinitionRelationship> relationship A link between this substance and another */
		public array $relationship = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRSubstanceDefinitionSourceMaterial sourceMaterial Material or taxonomic/anatomical source */
		public ?FHIRSubstanceDefinitionSourceMaterial $sourceMaterial = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
