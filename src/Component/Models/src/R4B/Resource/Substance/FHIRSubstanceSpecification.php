<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceSpecification
 *
 * @description The detailed description of a substance, typically at a level beyond what is used for prescribing.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'SubstanceSpecification',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstanceSpecification',
    fhirVersion: 'R4B',
)]
class FHIRSubstanceSpecification extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Identifier by which this substance is known */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRCodeableConcept|null type High level categorization, e.g. polymer or nucleic acid */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null status Status of substance within the catalogue e.g. approved */
        public ?\FHIRCodeableConcept $status = null,
        /** @var FHIRCodeableConcept|null domain If the substance applies to only human or veterinary use */
        public ?\FHIRCodeableConcept $domain = null,
        /** @var FHIRString|string|null description Textual description of the substance */
        public \FHIRString|string|null $description = null,
        /** @var array<FHIRReference> source Supporting literature */
        public array $source = [],
        /** @var FHIRString|string|null comment Textual comment about this record of a substance */
        public \FHIRString|string|null $comment = null,
        /** @var array<FHIRSubstanceSpecificationMoiety> moiety Moiety, for structural modifications */
        public array $moiety = [],
        /** @var array<FHIRSubstanceSpecificationProperty> property General specifications for this substance, including how it is related to other substances */
        public array $property = [],
        /** @var FHIRReference|null referenceInformation General information detailing this substance */
        public ?\FHIRReference $referenceInformation = null,
        /** @var FHIRSubstanceSpecificationStructure|null structure Structural information */
        public ?\FHIRSubstanceSpecificationStructure $structure = null,
        /** @var array<FHIRSubstanceSpecificationCode> code Codes associated with the substance */
        public array $code = [],
        /** @var array<FHIRSubstanceSpecificationName> name Names applicable to this substance */
        public array $name = [],
        /** @var array<FHIRSubstanceSpecificationStructureIsotopeMolecularWeight> molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
        public array $molecularWeight = [],
        /** @var array<FHIRSubstanceSpecificationRelationship> relationship A link between this substance and another, with details of the relationship */
        public array $relationship = [],
        /** @var FHIRReference|null nucleicAcid Data items specific to nucleic acids */
        public ?\FHIRReference $nucleicAcid = null,
        /** @var FHIRReference|null polymer Data items specific to polymers */
        public ?\FHIRReference $polymer = null,
        /** @var FHIRReference|null protein Data items specific to proteins */
        public ?\FHIRReference $protein = null,
        /** @var FHIRReference|null sourceMaterial Material or taxonomic/anatomical source for the substance */
        public ?\FHIRReference $sourceMaterial = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
