<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceSourceMaterial
 *
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
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null sourceMaterialClass General high level classification of the source material specific to the origin of the material */
        public ?\FHIRCodeableConcept $sourceMaterialClass = null,
        /** @var FHIRCodeableConcept|null sourceMaterialType The type of the source material shall be specified based on a controlled vocabulary. For vaccines, this subclause refers to the class of infectious agent */
        public ?\FHIRCodeableConcept $sourceMaterialType = null,
        /** @var FHIRCodeableConcept|null sourceMaterialState The state of the source material when extracted */
        public ?\FHIRCodeableConcept $sourceMaterialState = null,
        /** @var FHIRIdentifier|null organismId The unique identifier associated with the source material parent organism shall be specified */
        public ?\FHIRIdentifier $organismId = null,
        /** @var FHIRString|string|null organismName The organism accepted Scientific name shall be provided based on the organism taxonomy */
        public \FHIRString|string|null $organismName = null,
        /** @var array<FHIRIdentifier> parentSubstanceId The parent of the herbal drug Ginkgo biloba, Leaf is the substance ID of the substance (fresh) of Ginkgo biloba L. or Ginkgo biloba L. (Whole plant) */
        public array $parentSubstanceId = [],
        /** @var array<FHIRString|string> parentSubstanceName The parent substance of the Herbal Drug, or Herbal preparation */
        public array $parentSubstanceName = [],
        /** @var array<FHIRCodeableConcept> countryOfOrigin The country where the plant material is harvested or the countries where the plasma is sourced from as laid down in accordance with the Plasma Master File. For “Plasma-derived substances” the attribute country of origin provides information about the countries used for the manufacturing of the Cryopoor plama or Crioprecipitate */
        public array $countryOfOrigin = [],
        /** @var array<FHIRString|string> geographicalLocation The place/region where the plant is harvested or the places/regions where the animal source material has its habitat */
        public array $geographicalLocation = [],
        /** @var FHIRCodeableConcept|null developmentStage Stage of life for animals, plants, insects and microorganisms. This information shall be provided only when the substance is significantly different in these stages (e.g. foetal bovine serum) */
        public ?\FHIRCodeableConcept $developmentStage = null,
        /** @var array<FHIRSubstanceSourceMaterialFractionDescription> fractionDescription Many complex materials are fractions of parts of plants, animals, or minerals. Fraction elements are often necessary to define both Substances and Specified Group 1 Substances. For substances derived from Plants, fraction information will be captured at the Substance information level ( . Oils, Juices and Exudates). Additional information for Extracts, such as extraction solvent composition, will be captured at the Specified Substance Group 1 information level. For plasma-derived products fraction information will be captured at the Substance and the Specified Substance Group 1 levels */
        public array $fractionDescription = [],
        /** @var FHIRSubstanceSourceMaterialOrganism|null organism This subclause describes the organism which the substance is derived from. For vaccines, the parent organism shall be specified based on these subclause elements. As an example, full taxonomy will be described for the Substance Name: ., Leaf */
        public ?\FHIRSubstanceSourceMaterialOrganism $organism = null,
        /** @var array<FHIRSubstanceSourceMaterialPartDescription> partDescription To do */
        public array $partDescription = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
