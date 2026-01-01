<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceSourceMaterial
 * @description Source material shall capture information on the taxonomic and anatomical origins as well as the fraction of a material that can result in or can be modified to form a substance. This set of data elements shall be used to define polymer substances isolated from biological matrices. Taxonomic and anatomical origins shall be described using a controlled vocabulary as required. This information is captured for naturally derived polymers ( . starch) and structurally diverse substances. For Organisms belonging to the Kingdom Plantae the Substance level defines the fresh material of a single species or infraspecies, the Herbal Drug and the Herbal preparation. For Herbal preparations, the fraction information will be captured at the Substance information level and additional information for herbal extracts will be captured at the Specified Substance Group 1 information level. See for further explanation the Substance Class: Structurally Diverse and the herbal annex.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'SubstanceSourceMaterial',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/SubstanceSourceMaterial',
	fhirVersion: 'R5',
)]
class FHIRSubstanceSourceMaterial extends FHIRDomainResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept sourceMaterialClass General high level classification of the source material specific to the origin of the material */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $sourceMaterialClass = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept sourceMaterialType The type of the source material shall be specified based on a controlled vocabulary. For vaccines, this subclause refers to the class of infectious agent */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $sourceMaterialType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept sourceMaterialState The state of the source material when extracted */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $sourceMaterialState = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier organismId The unique identifier associated with the source material parent organism shall be specified */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier $organismId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string organismName The organism accepted Scientific name shall be provided based on the organism taxonomy */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $organismName = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> parentSubstanceId The parent of the herbal drug Ginkgo biloba, Leaf is the substance ID of the substance (fresh) of Ginkgo biloba L. or Ginkgo biloba L. (Whole plant) */
		public array $parentSubstanceId = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> parentSubstanceName The parent substance of the Herbal Drug, or Herbal preparation */
		public array $parentSubstanceName = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> countryOfOrigin The country where the plant material is harvested or the countries where the plasma is sourced from as laid down in accordance with the Plasma Master File. For “Plasma-derived substances” the attribute country of origin provides information about the countries used for the manufacturing of the Cryopoor plama or Crioprecipitate */
		public array $countryOfOrigin = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> geographicalLocation The place/region where the plant is harvested or the places/regions where the animal source material has its habitat */
		public array $geographicalLocation = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept developmentStage Stage of life for animals, plants, insects and microorganisms. This information shall be provided only when the substance is significantly different in these stages (e.g. foetal bovine serum) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $developmentStage = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSourceMaterialFractionDescription> fractionDescription Many complex materials are fractions of parts of plants, animals, or minerals. Fraction elements are often necessary to define both Substances and Specified Group 1 Substances. For substances derived from Plants, fraction information will be captured at the Substance information level ( . Oils, Juices and Exudates). Additional information for Extracts, such as extraction solvent composition, will be captured at the Specified Substance Group 1 information level. For plasma-derived products fraction information will be captured at the Substance and the Specified Substance Group 1 levels */
		public array $fractionDescription = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSourceMaterialOrganism organism This subclause describes the organism which the substance is derived from. For vaccines, the parent organism shall be specified based on these subclause elements. As an example, full taxonomy will be described for the Substance Name: ., Leaf */
		public ?FHIRSubstanceSourceMaterialOrganism $organism = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSubstanceSourceMaterialPartDescription> partDescription To do */
		public array $partDescription = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
