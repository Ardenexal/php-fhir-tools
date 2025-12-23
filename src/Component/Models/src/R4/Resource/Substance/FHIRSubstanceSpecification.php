<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceSpecification
 * @description The detailed description of a substance, typically at a level beyond what is used for prescribing.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'SubstanceSpecification',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/SubstanceSpecification',
	fhirVersion: 'R4',
)]
class FHIRSubstanceSpecification extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRIdentifier identifier Identifier by which this substance is known */
		public ?FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept type High level categorization, e.g. polymer or nucleic acid */
		public ?FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept status Status of substance within the catalogue e.g. approved */
		public ?FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept domain If the substance applies to only human or veterinary use */
		public ?FHIRCodeableConcept $domain = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description Textual description of the substance */
		public FHIRString|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> source Supporting literature */
		public array $source = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string comment Textual comment about this record of a substance */
		public FHIRString|string|null $comment = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSpecificationMoiety> moiety Moiety, for structural modifications */
		public array $moiety = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSpecificationProperty> property General specifications for this substance, including how it is related to other substances */
		public array $property = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference referenceInformation General information detailing this substance */
		public ?FHIRReference $referenceInformation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSpecificationStructure structure Structural information */
		public ?FHIRSubstanceSpecificationStructure $structure = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSpecificationCode> code Codes associated with the substance */
		public array $code = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSpecificationName> name Names applicable to this substance */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSpecificationStructureIsotopeMolecularWeight> molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
		public array $molecularWeight = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSubstanceSpecificationRelationship> relationship A link between this substance and another, with details of the relationship */
		public array $relationship = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference nucleicAcid Data items specific to nucleic acids */
		public ?FHIRReference $nucleicAcid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference polymer Data items specific to polymers */
		public ?FHIRReference $polymer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference protein Data items specific to proteins */
		public ?FHIRReference $protein = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference sourceMaterial Material or taxonomic/anatomical source for the substance */
		public ?FHIRReference $sourceMaterial = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
