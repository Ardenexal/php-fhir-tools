<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSourceMaterial\SubstanceSourceMaterialFractionDescription;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSourceMaterial\SubstanceSourceMaterialOrganism;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSourceMaterial\SubstanceSourceMaterialPartDescription;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceSourceMaterial
 *
 * @description Source material shall capture information on the taxonomic and anatomical origins as well as the fraction of a material that can result in or can be modified to form a substance. This set of data elements shall be used to define polymer substances isolated from biological matrices. Taxonomic and anatomical origins shall be described using a controlled vocabulary as required. This information is captured for naturally derived polymers ( . starch) and structurally diverse substances. For Organisms belonging to the Kingdom Plantae the Substance level defines the fresh material of a single species or infraspecies, the Herbal Drug and the Herbal preparation. For Herbal preparations, the fraction information will be captured at the Substance information level and additional information for herbal extracts will be captured at the Specified Substance Group 1 information level. See for further explanation the Substance Class: Structurally Diverse and the herbal annex.
 */
#[FhirResource(
    type: 'SubstanceSourceMaterial',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstanceSourceMaterial',
    fhirVersion: 'R4',
)]
class SubstanceSourceMaterialResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null sourceMaterialClass General high level classification of the source material specific to the origin of the material */
        public ?CodeableConcept $sourceMaterialClass = null,
        /** @var CodeableConcept|null sourceMaterialType The type of the source material shall be specified based on a controlled vocabulary. For vaccines, this subclause refers to the class of infectious agent */
        public ?CodeableConcept $sourceMaterialType = null,
        /** @var CodeableConcept|null sourceMaterialState The state of the source material when extracted */
        public ?CodeableConcept $sourceMaterialState = null,
        /** @var Identifier|null organismId The unique identifier associated with the source material parent organism shall be specified */
        public ?Identifier $organismId = null,
        /** @var StringPrimitive|string|null organismName The organism accepted Scientific name shall be provided based on the organism taxonomy */
        public StringPrimitive|string|null $organismName = null,
        /** @var array<Identifier> parentSubstanceId The parent of the herbal drug Ginkgo biloba, Leaf is the substance ID of the substance (fresh) of Ginkgo biloba L. or Ginkgo biloba L. (Whole plant) */
        public array $parentSubstanceId = [],
        /** @var array<StringPrimitive|string> parentSubstanceName The parent substance of the Herbal Drug, or Herbal preparation */
        public array $parentSubstanceName = [],
        /** @var array<CodeableConcept> countryOfOrigin The country where the plant material is harvested or the countries where the plasma is sourced from as laid down in accordance with the Plasma Master File. For “Plasma-derived substances” the attribute country of origin provides information about the countries used for the manufacturing of the Cryopoor plama or Crioprecipitate */
        public array $countryOfOrigin = [],
        /** @var array<StringPrimitive|string> geographicalLocation The place/region where the plant is harvested or the places/regions where the animal source material has its habitat */
        public array $geographicalLocation = [],
        /** @var CodeableConcept|null developmentStage Stage of life for animals, plants, insects and microorganisms. This information shall be provided only when the substance is significantly different in these stages (e.g. foetal bovine serum) */
        public ?CodeableConcept $developmentStage = null,
        /** @var array<SubstanceSourceMaterialFractionDescription> fractionDescription Many complex materials are fractions of parts of plants, animals, or minerals. Fraction elements are often necessary to define both Substances and Specified Group 1 Substances. For substances derived from Plants, fraction information will be captured at the Substance information level ( . Oils, Juices and Exudates). Additional information for Extracts, such as extraction solvent composition, will be captured at the Specified Substance Group 1 information level. For plasma-derived products fraction information will be captured at the Substance and the Specified Substance Group 1 levels */
        public array $fractionDescription = [],
        /** @var SubstanceSourceMaterialOrganism|null organism This subclause describes the organism which the substance is derived from. For vaccines, the parent organism shall be specified based on these subclause elements. As an example, full taxonomy will be described for the Substance Name: ., Leaf */
        public ?SubstanceSourceMaterialOrganism $organism = null,
        /** @var array<SubstanceSourceMaterialPartDescription> partDescription To do */
        public array $partDescription = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
