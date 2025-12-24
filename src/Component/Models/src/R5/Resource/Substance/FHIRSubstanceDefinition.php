<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceDefinition
 *
 * @description The detailed description of a substance, typically at a level beyond what is used for prescribing.
 */
#[FhirResource(
    type: 'SubstanceDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstanceDefinition',
    fhirVersion: 'R5',
)]
class FHIRSubstanceDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifier by which this substance is known */
        public array $identifier = [],
        /** @var FHIRString|string|null version A business level version identifier of the substance */
        public FHIRString|string|null $version = null,
        /** @var FHIRCodeableConcept|null status Status of substance within the catalogue e.g. active, retired */
        public ?FHIRCodeableConcept $status = null,
        /** @var array<FHIRCodeableConcept> classification A categorization, high level e.g. polymer or nucleic acid, or food, chemical, biological, or lower e.g. polymer linear or branch chain, or type of impurity */
        public array $classification = [],
        /** @var FHIRCodeableConcept|null domain If the substance applies to human or veterinary use */
        public ?FHIRCodeableConcept $domain = null,
        /** @var array<FHIRCodeableConcept> grade The quality standard, established benchmark, to which substance complies (e.g. USP/NF, BP) */
        public array $grade = [],
        /** @var FHIRMarkdown|null description Textual description of the substance */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRReference> informationSource Supporting literature */
        public array $informationSource = [],
        /** @var array<FHIRAnnotation> note Textual comment about the substance's catalogue or registry record */
        public array $note = [],
        /** @var array<FHIRReference> manufacturer The entity that creates, makes, produces or fabricates the substance */
        public array $manufacturer = [],
        /** @var array<FHIRReference> supplier An entity that is the source for the substance. It may be different from the manufacturer */
        public array $supplier = [],
        /** @var array<FHIRSubstanceDefinitionMoiety> moiety Moiety, for structural modifications */
        public array $moiety = [],
        /** @var array<FHIRSubstanceDefinitionCharacterization> characterization General specifications for this substance */
        public array $characterization = [],
        /** @var array<FHIRSubstanceDefinitionProperty> property General specifications for this substance */
        public array $property = [],
        /** @var FHIRReference|null referenceInformation General information detailing this substance */
        public ?FHIRReference $referenceInformation = null,
        /** @var array<FHIRSubstanceDefinitionMolecularWeight> molecularWeight The average mass of a molecule of a compound */
        public array $molecularWeight = [],
        /** @var FHIRSubstanceDefinitionStructure|null structure Structural information */
        public ?FHIRSubstanceDefinitionStructure $structure = null,
        /** @var array<FHIRSubstanceDefinitionCode> code Codes associated with the substance */
        public array $code = [],
        /** @var array<FHIRSubstanceDefinitionName> name Names applicable to this substance */
        public array $name = [],
        /** @var array<FHIRSubstanceDefinitionRelationship> relationship A link between this substance and another */
        public array $relationship = [],
        /** @var FHIRReference|null nucleicAcid Data items specific to nucleic acids */
        public ?FHIRReference $nucleicAcid = null,
        /** @var FHIRReference|null polymer Data items specific to polymers */
        public ?FHIRReference $polymer = null,
        /** @var FHIRReference|null protein Data items specific to proteins */
        public ?FHIRReference $protein = null,
        /** @var FHIRSubstanceDefinitionSourceMaterial|null sourceMaterial Material or taxonomic/anatomical source */
        public ?FHIRSubstanceDefinitionSourceMaterial $sourceMaterial = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
